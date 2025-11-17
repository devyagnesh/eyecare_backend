<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNotificationRequest;
use App\Http\Traits\HandlesAjaxResponses;
use App\Models\User;
use App\Models\Store;
use App\Services\NotificationService;
use Illuminate\Http\Request;

/**
 * Notification Controller
 * 
 * Handles admin panel requests for push notifications management.
 * 
 * @package App\Http\Controllers\Admin
 */
class NotificationController extends Controller
{
    use HandlesAjaxResponses;

    /**
     * Create a new controller instance.
     *
     * @param NotificationService $notificationService
     */
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'type' => $request->get('type'),
            'status' => $request->get('status'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
        ];

        $notifications = $this->notificationService->getNotifications($filters, true);

        return view('admin.notifications.index', compact('notifications', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::where('is_blocked', false)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        
        $stores = Store::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.notifications.create', compact('users', 'stores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNotificationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreNotificationRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Parse JSON data if provided as string
            $dataPayload = [];
            $dataInput = $request->input('data');
            if ($dataInput) {
                if (is_string($dataInput)) {
                    $dataPayload = json_decode($dataInput, true) ?? [];
                } else {
                    $dataPayload = is_array($dataInput) ? $dataInput : [];
                }
            }

            $createdBy = auth()->id();

            switch ($data['type']) {
                case 'all':
                    $notification = $this->notificationService->sendToAll(
                        $data['title'],
                        $data['body'],
                        $dataPayload,
                        $createdBy
                    );
                    break;

                case 'user':
                    $notification = $this->notificationService->sendToUser(
                        $data['user_id'],
                        $data['title'],
                        $data['body'],
                        $dataPayload,
                        $createdBy
                    );
                    break;

                case 'store':
                    $notification = $this->notificationService->sendToStore(
                        $data['store_id'],
                        $data['title'],
                        $data['body'],
                        $dataPayload,
                        $createdBy
                    );
                    break;

                default:
                    return $this->errorResponse('Invalid notification type.');
            }

            return $this->successResponse(
                'Notification sent successfully!',
                route('admin.notifications.index')
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse(
                'Failed to send notification: ' . $e->getMessage()
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $notification = \App\Models\Notification::with(['user', 'store', 'creator'])
            ->findOrFail($id);

        return view('admin.notifications.show', compact('notification'));
    }
}

