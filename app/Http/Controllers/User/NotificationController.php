<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return response()->json($notifications);
    }
    
    public function unreadCount()
    {
        $user = Auth::user();
        
        $count = $user->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }
    
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marquée comme lue']);
        }
        
        return response()->json(['error' => 'Notification non trouvée'], 404);
    }
    
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        $user->unreadNotifications()->update(['read_at' => now()]);
        
        return response()->json(['message' => 'Toutes les notifications marquées comme lues']);
    }
    
    public function delete($id)
    {
        $user = Auth::user();
        
        $user->notifications()->where('id', $id)->delete();
        
        return response()->json(['message' => 'Notification supprimée']);
    }
    
    public function clearAll()
    {
        $user = Auth::user();
        
        $user->notifications()->delete();
        
        return response()->json(['message' => 'Toutes les notifications supprimées']);
    }
    
    public function preferences()
    {
        $user = Auth::user();
        
        // Récupérer les préférences de notification
        $preferences = $user->notificationPreferences ?? [
            'new_content' => true,
            'comments' => true,
            'likes' => true,
            'messages' => true,
            'marketing' => false,
            'email_notifications' => true,
            'push_notifications' => true,
        ];
        
        return response()->json($preferences);
    }
    
    public function updatePreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_content' => 'boolean',
            'comments' => 'boolean',
            'likes' => 'boolean',
            'messages' => 'boolean',
            'marketing' => 'boolean',
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $user = Auth::user();
        
        // Mettre à jour les préférences
        $user->notificationPreferences = $request->all();
        $user->save();
        
        return response()->json([
            'message' => 'Préférences de notification mises à jour',
            'preferences' => $user->notificationPreferences,
        ]);
    }
}