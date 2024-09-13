<?php

namespace App\Http\Controllers;

use App\Models\OrderInfos;
use App\Models\MemberNotes;
use App\Models\RestComments;
use App\Models\NotesComments;
use App\Models\RestFavorites;
use App\Models\NotesFavorites;
use App\Http\Resources\OrderResource;
use App\Http\Resources\NoteResource;
use App\Http\Resources\NotesFavoriteResource;
use App\Http\Resources\NotesCommentResource;
use App\Http\Resources\RestFavoriteResource;
use App\Http\Resources\RestCommentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    public function all(Request $request)
    {
        try {
            // 現成
            $ordersResponse = $this->orderInfos($request);
            // dd($ordersResponse);

            $notesResponse = $this->memberNotes($request);
            // dd($notesResponse);

            $commentsResponse = $this->comments($request);
            // dd($commentsResponse);

            $favoritesResponse = $this->favorites($request);
            // dd($favoritesResponse);

    
            // 根據 dd 解構
            $orders = $ordersResponse->resource->items();
            $notes = json_decode($notesResponse->content(), true)['query_result']['data'] ?? [];
            $comments = json_decode($commentsResponse->content(), true);
            $favorites = json_decode($favoritesResponse->content(), true);
    
            $restComments = $comments['restComments'] ?? [];
            $notesComments = $comments['notesComments'] ?? [];
            $restFavorites = $favorites['restFavorites'] ?? [];
            $notesFavorites = $favorites['notesFavorites'] ?? [];
    
            // 合併所有數據
            $allActivities = collect()
                ->concat(collect($orders)->map(fn($item) => ['type' => 'order'] + $item->toArray($request)))
                ->concat(collect($notes)->map(fn($item) => ['type' => 'note'] + (array)$item))
                ->concat(collect($restComments)->map(fn($item) => ['type' => 'restComment'] + (array)$item))
                ->concat(collect($notesComments)->map(fn($item) => ['type' => 'noteComment'] + (array)$item))
                ->concat(collect($restFavorites)->map(fn($item) => ['type' => 'restFavorite'] + (array)$item))
                ->concat(collect($notesFavorites)->map(fn($item) => ['type' => 'noteFavorite'] + (array)$item))
                ->sortByDesc('created_at')
                ->take(20);
    
            return response()->json([
                'all' => $allActivities,
                'orders' => $orders,
                'notes' => $notes,
                'comments' => [
                    'restComments' => $restComments,
                    'notesComments' => $notesComments
                ],
                'favorites' => [
                    'restFavorites' => $restFavorites,
                    'notesFavorites' => $notesFavorites
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => '內部伺服器錯誤',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
    public function orderInfos(Request $request)
    {
        try {
            $result = OrderInfos::with([
                'RestInfos.Users',
                'OrderStatuses',
                'OrderDetails',
                // 'Members.Users'
            ])->latest()->paginate(4);
            return OrderResource::collection($result);
        } catch (\Exception $e) {
            return response()->json(['error' => '內部伺服器錯誤'], 500);
        }
    }

    public function memberNotes(Request $request)
    {
        try {
            $result = MemberNotes::with([
                'RestInfos.Users',
                'RestInfos.RestItems.RestDiscount',
                'RestInfos.FiltClasses',
                'NotesComments',
                'NotesFavorites',
                'Members.Users'
            ])->latest()->paginate(4);

            return response()->json([
                'query_result' => $result->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => '內部伺服器錯誤',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function comments(Request $request)
    {
        try {
            $restComments = RestComments::with([
                'RestInfos.Users',
                'RestInfos.FiltClasses',
                'Members.Users',
                'Members.MemberReviews'
            ])->latest()->get();

            $notesComments = NotesComments::with([
                'MemberNotes.RestInfos.Users',
                'MemberNotes.RestInfos.FiltClasses',
                'Members.Users'
                
            ])->latest()->get();

            $comments = $restComments->concat($notesComments)->sortByDesc('created_at')->take(4);

            $restCommentResources = RestCommentResource::collection($comments->filter(function ($comment) {
                return $comment instanceof RestComments;
            }));

            $notesCommentResources = NotesCommentResource::collection($comments->filter(function ($comment) {
                return $comment instanceof NotesComments;
            }));
            return response()->json([
                'restComments' => $restCommentResources,
                'notesComments' => $notesCommentResources
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => '內部伺服器錯誤',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function favorites(Request $request)
    {
        try {
            $restFavorites = RestFavorites::with([
                'RestInfos.Users',
                'RestInfos.FiltClasses',
                'RestInfos.MemberReviews',
                'RestInfos.SuitableFor.FiltPurposes'
            ])->latest()->get();

            $notesFavorites = NotesFavorites::with([
                'MemberNotes',
                'MemberNotes.RestInfos.Users',
                'MemberNotes.RestInfos.FiltClasses',
                'MemberNotes.NotesComments',
                'MemberNotes.NotesFavorites'
            ])->latest()->get();

            $favorites = $restFavorites->concat($notesFavorites)->sortByDesc('created_at')->take(4);

            $restFavoriteResources = RestFavoriteResource::collection($favorites->filter(function ($favorite) {
                return $favorite instanceof RestFavorites;
            }));

            $notesFavoriteResources = NotesFavoriteResource::collection($favorites->filter(function ($favorite) {
                return $favorite instanceof NotesFavorites;
            }));

            return response()->json([
                'restFavorites' => $restFavoriteResources,
                'notesFavorites' => $notesFavoriteResources
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => '內部伺服器錯誤',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }


    public function updateRestComment(Request $request, $id)
    {
        $comment = RestComments::findOrFail($id);
        $comment->update($request->all());
        return response()->json(['success' => true]);
    }

    public function updateNotesComment(Request $request, $id)
    {
        $comment = NotesComments::findOrFail($id);
        $comment->update($request->all());
        return new NotesCommentResource($comment);
    }

    public function deleteRestComment($id)
    {
        $comment = RestComments::findOrFail($id);
        $comment->delete();
        return response()->json(null, 204);
    }

    public function deleteNotesComment($id)
    {
        $comment = NotesComments::findOrFail($id);
        $comment->delete();
        return response()->json(null, 204);
    }

    public function deleteRestFavorite($id)
    {
        $favorite = RestFavorites::findOrFail($id);
        $favorite->delete();
        return response()->json(null, 204);
    }

    public function deleteNotesFavorite($id)
    {
        $favorite = NotesFavorites::findOrFail($id);
        $favorite->delete();
        return response()->json(null, 204);
    }
}

