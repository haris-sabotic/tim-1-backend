<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderArticle;
use App\Models\OrderArticleRating;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function makeOrder(Request $request)
    {
        $this->validate($request, [
            'articles' => 'required',
            'articles.*.article_id' => 'required|exists:articles,id',
            'articles.*.count' => 'required',
        ]);

        // early return if trying to order before the set start time
        $startTime = env("ORDER_START_TIME");
        if (time() <= strtotime($startTime)) {
            return response()->json(['error' => "You can't order before $startTime."], 400);
        }

        $userId = $request->user()->id;

        $date = $this->getOrderDate($request);

        // if the user's already ordered for the given date, delete that order and its articles and article ratings
        $order = $this->getUserOrder($request, $date);
        if ($order->first() != null) {
            $orderId = $order->first()->id;

            $orderArticles = OrderArticle::where('order_id', $orderId)->get();
            foreach ($orderArticles as $orderArticle) {
                OrderArticleRating::where('order_article_id', $orderArticle->id)->delete();
            }
            OrderArticle::where('order_id', $orderId)->delete();

            $order->delete();
        }

        // create order
        $order = Order::create([
            'user_id' => $userId,
            'state' => 'preparing',
            'comment' => $request->comment,
            'date' => $date
        ]);

        $orderId = $order->id;


        // create the new order's articles
        foreach ($request->articles as $article) {
            OrderArticle::create([
                "order_id" => $orderId,
                "article_id" => $article['article_id'],
                "count" => $article['count']
            ]);
        }

        return response()->json(['success' => "Saved order"], 200);
    }

    public function getOrder(Request $request)
    {
        $date = $this->getOrderDate($request);
        return $this->getUserOrder($request, $date)->first()->load('articles');
    }

    public function postRatings(Request $request)
    {
        $this->validate($request, [
            'ratings.*.article_id' => 'required|exists:articles,id',
            'ratings.*.stars' => 'required',
        ]);

        $date = $this->getOrderDate($request);

        $order = $this->getUserOrder($request, $date)->first();
        if ($order == null) {
            return response()->json(['error' => "User hasn't made an order for that day yet."], 400);
        }

        $orderId = $order->id;

        // delete previous article ratings
        $orderArticles = OrderArticle::where('order_id', $orderId)->get();
        foreach ($orderArticles as $orderArticle) {
            OrderArticleRating::where('order_article_id', $orderArticle->id)->delete();
        }

        // create new article ratings
        foreach ($request->ratings as $r) {
            $order_article = OrderArticle::where("order_id", '=', $orderId, 'and')->where('article_id', $r['article_id'])->first();
            // don't create the rating if it's for an article that doesn't exist in the current order
            if ($order_article) {
                $comment = null;
                if (isset($r['comment'])) {
                    $comment = $r['comment'];
                }

                OrderArticleRating::create([
                    "order_article_id" => $order_article->id,
                    'stars' => $r['stars'],
                    'comment' => $comment
                ]);
            }
        }

        return response()->json(['success' => "Saved all ratings"], 200);
    }

    public function getRatings(Request $request)
    {
        $date = $this->getOrderDate($request);

        $order = $this->getUserOrder($request, $date)->first();
        if ($order == null) {
            return response()->json(['error' => "User hasn't made an order for that day yet."], 400);
        }

        $orderId = $order->id;

        $result = [];

        $orderArticles = OrderArticle::where('order_id', $orderId)->get();
        foreach ($orderArticles as $orderArticle) {
            $rating = OrderArticleRating::where('order_article_id', $orderArticle->id)->first();

            if ($rating) {
                array_push($result, [
                    'article_id' => $orderArticle->article_id,
                    'stars' => $rating->stars,
                    'comment' => $rating->comment,
                ]);
            }
        }

        return $result;
    }

    private function getOrderDate(Request $request)
    {
        // get date from request or use today's date as fallback
        $date = date('Y-m-d');
        if ($request->date) {
            $date = $request->date;
        }

        return $date;
    }

    private function getUserOrder(Request $request, $date)
    {
        // if the user's already ordered for the given date, delete that order and its articles and article ratings
        $order = Order::where('user_id', '=', $request->user()->id, 'and')->where('date', '=', $date);

        return $order;
    }
}
