<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// Models
use App\Models\SupportTicket;
use App\Models\SupportTicket\Comment as SupportTicketComment;
use App\Models\User;
use App\Models\Fulfillment;
use App\Models\Fulfillment\ProductPayment as FulfillmentProductPayment;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Supplier;
// Enums
use App\Enums\SupportTicketEnum;
use App\Enums\FulfillmentEnum;
use App\Enums\ProductPaymentEnum;
use App\Enums\InvoiceEnum;
use App\Enums\ResponseMessageEnum;
use App\Enums\GeneralEnum;
use App\Models\Invoice;
// Helpers
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $staff = $user->staff;

        // Get the value of start of this week and end of this week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Get the details for fulfillments
        $fulfillmentsDetails = $this->getFulfillmentRecords($user, $startOfWeek, $endOfWeek);
        // Get the details for fulfillment product payments
        $fulfillmentProductPayments = $this->getFulfillmentProductPaymentsRecords($user, $startOfWeek, $endOfWeek);
        // Get the details for invoices
        $invoicesDetails = $this->getInvoicesRecords($user, $startOfWeek, $endOfWeek);

        return view('admin.dashboard.index', [
            'startOfWeek' => $startOfWeek->format('D d M Y'),
            'endOfWeek' => $endOfWeek->format('D d M Y'),
            'fulfillmentsDetails' => $fulfillmentsDetails,
            'fulfillmentProductPayments' => $fulfillmentProductPayments,
            'invoicesDetails' => $invoicesDetails,
        ]);
    }

    /** Get fulfillments */
    private function getFulfillmentRecords(User $user, string $startOfWeek, string $endOfWeek)
    {
        $createdThisWeek = Fulfillment::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                                    ->where('fulfillment_status', '!=', FulfillmentEnum::FULFILLMENT_STATUS_DELETE)        
                                    ->get();

        $waiting = Fulfillment::whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                            ->where('shipping_status', FulfillmentEnum::SHIPPING_SHIPPED)
                            ->get();

        $shipped = Fulfillment::whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                            ->where('shipping_status', FulfillmentEnum::SHIPPING_WAITING)
                            ->get();
                            
        $returned = Fulfillment::whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                            ->where('shipping_status', FulfillmentEnum::SHIPPING_RETURNED)
                            ->get();
                            
        $delivered = Fulfillment::whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                            ->where('shipping_status', FulfillmentEnum::SHIPPING_DELIVERED)
                            ->get();

        return [
            'created' => $createdThisWeek->count(),
            'waiting' => $waiting->count(),
            'shipped' => $shipped->count(),
            'returned' => $returned->count(),
            'delivered' => $delivered->count(),
        ];
    }

    /** Get fulfillment product payments */
    private function getFulfillmentProductPaymentsRecords(User $user, string $startOfWeek, string $endOfWeek)
    {        
        $allFulfillmentPayments = FulfillmentProductPayment::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                            ->where('status', '!=', ProductPaymentEnum::STATUS_DELETED)
                            ->get();

        $pendingPayments = FulfillmentProductPayment::whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                            ->where('status', ProductPaymentEnum::STATUS_PENDING)
                            ->get();

        $approvedPayments = FulfillmentProductPayment::whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                            ->where('status', ProductPaymentEnum::STATUS_APPROVED)
                            ->get();

        $declinedPayments = FulfillmentProductPayment::whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                            ->where('status', ProductPaymentEnum::STATUS_DECLINED)
                            ->get();
        
        return [
            'created' => $allFulfillmentPayments->count(),
            'pending' => $pendingPayments->count(),
            'approved' => $approvedPayments->count(),
            'declined' => $declinedPayments->count(),
        ];
    }

    /** Get invoices */
    private function getInvoicesRecords(User $user, string $startOfWeek, $endOfWeek)
    {
        $created = Invoice::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                        ->get();
                        
        $pending = Invoice::where('status', InvoiceEnum::STATUS_PENDING)
                        ->where('payment_status', InvoiceEnum::STATUS_UNPAID)
                        ->get();

        $unpaid = Invoice::whereNotIn('status', [InvoiceEnum::STATUS_CANCELLED, InvoiceEnum::STATUS_WAIVED])
                        ->where('payment_status', InvoiceEnum::STATUS_UNPAID)
                        ->get();
                        
        $overdue = Invoice::where('status', InvoiceEnum::STATUS_OVERDUE)
                        ->where('payment_status', InvoiceEnum::STATUS_UNPAID)
                        ->get();

        return [
            'created' => $created->count(),
            'pending' => $pending->count(),
            'unpaid' => $unpaid->count(),
            'overdue' => $overdue->count(),
        ];
    }
}
