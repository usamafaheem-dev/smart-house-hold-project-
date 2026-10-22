<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Payment;
use App\Models\Review;
use App\Models\ServiceCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed demo bookings, invoices, reviews, and complaints so the
     * completed features (payments, ratings, complaints, earnings,
     * analytics) have realistic data to display out of the box.
     *
     * All rows use fixed identifiers + firstOrCreate, so this seeder
     * is idempotent and safe to re-run.
     */
    public function run(): void
    {
        $customer = User::where('email', 'customer@household.com')->first();
        $provider = User::where('email', 'provider@household.com')->first();

        if (! $customer || ! $provider) {
            $this->command->warn('DemoDataSeeder skipped: base users not found. Run UserSeeder first.');
            return;
        }

        $categories = ServiceCategory::pluck('id', 'name'); // ['Cleaning' => 1, ...]

        // Each entry: [booking_id, category, status, assign provider?, days offset]
        $bookings = [
            ['BK-DEMO01', 'Cleaning',         'completed', true,  -20],
            ['BK-DEMO02', 'Plumbing',         'completed', true,  -14],
            ['BK-DEMO03', 'Electrician',      'completed', true,  -7],
            ['BK-DEMO04', 'Gardening',        'pending',   true,  -2],
            ['BK-DEMO05', 'Appliance Repair', 'pending',   false,  3],
        ];

        $created = [];

        foreach ($bookings as [$code, $catName, $status, $assignProvider, $offset]) {
            if (! isset($categories[$catName])) {
                continue;
            }

            $categoryId = $categories[$catName];
            $category = ServiceCategory::find($categoryId);
            $serviceDate = Carbon::now()->addDays($offset);

            $booking = Booking::firstOrCreate(
                ['booking_id' => $code],
                [
                    'customer_id'  => $customer->id,
                    'provider_id'  => $assignProvider ? $provider->id : null,
                    'category_id'  => $categoryId,
                    'booking_date' => Carbon::now()->addDays($offset - 2)->toDateString(),
                    'service_date' => $serviceDate->toDateString(),
                    'service_time' => '11:00',
                    'address'      => 'House #45, Block B, Model Town, Lahore',
                    'status'       => $status,
                    'total_amount' => $category->estimated_price ?? 2000,
                    'notes'        => 'Demo booking seeded for feature showcase.',
                ]
            );

            $created[$code] = $booking;
        }

        // --- Invoices for completed bookings ---
        // BK-DEMO01 -> paid (cash), BK-DEMO02 -> paid (card), BK-DEMO03 -> unpaid
        $this->makeInvoice($created, 'BK-DEMO01', 'INV-DEMO01', 'cash', true);
        $this->makeInvoice($created, 'BK-DEMO02', 'INV-DEMO02', 'card', true);
        $this->makeInvoice($created, 'BK-DEMO03', 'INV-DEMO03', null, false);

        // --- Reviews for completed + assigned bookings ---
        $this->makeReview($created, 'BK-DEMO01', $customer->id, $provider->id, 5, 'Excellent service, very thorough and professional!');
        $this->makeReview($created, 'BK-DEMO02', $customer->id, $provider->id, 4, 'Fixed the leak quickly. Good work.');
        // BK-DEMO03 intentionally left un-reviewed so "Rate Provider" is demoable.

        // --- Complaints ---
        Complaint::firstOrCreate(
            ['complaint_id' => 'CMP-DEMO1'],
            [
                'customer_id' => $customer->id,
                'booking_id'  => $created['BK-DEMO03']->id ?? null,
                'subject'     => 'Technician arrived late',
                'message'     => 'The electrician arrived nearly two hours after the scheduled time.',
                'status'      => 'open',
            ]
        );

        Complaint::firstOrCreate(
            ['complaint_id' => 'CMP-DEMO2'],
            [
                'customer_id' => $customer->id,
                'booking_id'  => $created['BK-DEMO01']->id ?? null,
                'subject'     => 'Billing question',
                'message'     => 'I wanted to confirm whether the cleaning charge included the sofa wash.',
                'status'      => 'resolved',
                'admin_reply' => 'Yes, the sofa wash was included in your package. Thank you for reaching out!',
                'resolved_at' => Carbon::now()->subDays(3),
            ]
        );

        $this->command->info('DemoDataSeeder: bookings, invoices, reviews, and complaints seeded.');
    }

    /**
     * Create (or backfill) an invoice for a completed booking.
     */
    private function makeInvoice(array $created, string $code, string $invoiceNumber, ?string $method, bool $paid): void
    {
        if (! isset($created[$code])) {
            return;
        }

        $booking = $created[$code];

        Payment::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'invoice_number'  => $invoiceNumber,
                'amount'          => $booking->total_amount ?? 0,
                'method'          => $paid ? $method : null,
                'status'          => $paid ? 'paid' : 'unpaid',
                'transaction_ref' => $paid ? 'TXN-'.strtoupper(substr(md5($code), 0, 10)) : null,
                'paid_at'         => $paid ? Carbon::now()->subDays(2) : null,
            ]
        );
    }

    /**
     * Create a review for a booking if one doesn't exist yet.
     */
    private function makeReview(array $created, string $code, int $customerId, int $providerId, int $rating, string $comment): void
    {
        if (! isset($created[$code])) {
            return;
        }

        Review::firstOrCreate(
            ['booking_id' => $created[$code]->id],
            [
                'customer_id' => $customerId,
                'provider_id' => $providerId,
                'rating'      => $rating,
                'comment'     => $comment,
            ]
        );
    }
}
