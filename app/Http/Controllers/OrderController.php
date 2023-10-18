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
            'expires_on' => 'required|date',
            'articles' => 'required',
            'articles.*.article_id' => 'required|exists:articles,id',
            'articles.*.count' => 'required',
        ]);

        // early return if trying to order before the set start time
        $startTime = env("ORDER_START_TIME");
        if (time() <= strtotime($startTime)) {
            return response()->json(['error' => "You can't order before $startTime."], 400);
        }

        $orderId = $request->user()->order_id; // current user's order

        // orderId will be null if the user's ordering for the first time, so create his order here
        if ($orderId == null) {
            $order = Order::create([
                'state' => 'preparing',
                'expires_on' => $request->expires_on,
                'comment' => $request->comment
            ]);

            $orderId = $order->id;

            // update user's order_id
            $user = User::find($request->user()->id);
            $user->order_id = $orderId;
            $user->save();
        } else { // otherwise, update the existing one
            $order = Order::find($orderId);
            $order->state = 'preparing';
            $order->expires_on = $request->expires_on;
            $order->comment = $request->comment;

            $order->save();
        }

        // delete article ratings and articles associated with the previous order
        $orderArticles = OrderArticle::where('order_id', $orderId)->get();
        foreach ($orderArticles as $orderArticle) {
            OrderArticleRating::where('order_article_id', $orderArticle->id)->delete();
        }
        OrderArticle::where('order_id', $orderId)->delete();

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
        return Order::find($request->user()->id)->get()->load('articles');
    }

    public function postRatings(Request $request)
    {
        $this->validate($request, [
            'data.*.article_id' => 'required|exists:articles,id',
            'data.*.stars' => 'required',
        ]);


        $orderId = $request->user()->order_id;
        if ($orderId == null) {
            return response()->json(['error' => "User hasn't made an order yet."], 400);
        }

        // delete previous article ratings
        $orderArticles = OrderArticle::where('order_id', $orderId)->get();
        foreach ($orderArticles as $orderArticle) {
            OrderArticleRating::where('order_article_id', $orderArticle->id)->delete();
        }

        // create new article ratings
        foreach ($request->data as $r) {
            $order_article = OrderArticle::where("order_id", '=', $orderId, 'and')->where('article_id', $r['article_id'])->first();
            // don't create the rating if it's for an article that doesn't exist in the current order
            if ($order_article) {
                OrderArticleRating::create([
                    "order_article_id" => $order_article->id,
                    'stars' => $r['stars'],
                    'comment' => $r['comment']
                ]);
            }
        }

        return response()->json(['success' => "Saved all ratings"], 200);
    }

    public function getRatings(Request $request)
    {
        $orderId = $request->user()->order_id;
        if ($orderId == null) {
            return response()->json(['error' => "User hasn't made an order yet."], 400);
        }

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
}
