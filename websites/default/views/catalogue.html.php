<div class="container">
    <h1>
        <?= $auction->title ?>
        <?= $auction->sold == "Sold" ? "Sold" : "" ?>
    </h1>
    <p><strong>Auction Name:</strong>
        <?= isset($lot->title) ? htmlspecialchars($lot->title) : '' ?>
    </p>
    <p><strong>Description:</strong>
        <?= isset($auction->description) ? htmlspecialchars($auction->description) : '' ?>
    </p>
    <?php if (file_exists('./images/auctions/' . $auction->id . '.jpg')) { ?>
    <img src="/images/auctions/<?= $auction->id ?>.jpg" />
    <?php } ?>
    <p><strong>Artist Name:</strong>
        <?= isset($auction->artist_name) ? htmlspecialchars($auction->artist_name) : '' ?>
    </p>


    <p>
        <strong>Estimated Price:</strong>
        £
        <?= htmlspecialchars($auction->lower_est) ?> - £
        <?= $auction->higher_est ?>
    </p>
    <?php
    use http\Model\Category;

    $categories = Category::getCategories();
    $catarr = $categories[$auction->category]->getFieldsArray();
    foreach ($catarr as $field) {
        $value = $auction->category_fields[$field] ?? "";
        echo "<p><strong>$field:</strong> " . htmlspecialchars($value) . "</p>";
    }
    ?>
    <p>
        <strong>Auction Location:</strong>
        <?= $lot->location ?>
    </p>
    <hr>
    <p>
        <strong>Auction Date:</strong>
        <?= isset($lot->date) ? htmlspecialchars($lot->date) : '' ?>
    </p>
    <p>
        <strong>Auction period:</strong>
        <?= $lot->period ?>
    </p>
    <p>
        <strong>Current highest bid:</strong>
        <?php
        if (isset($bid) && count($bid) > 0) {
            $bid = $bid[0];
        } else
            $bid = null ?>
        <?= isset($bid) ? $bid->bid_amount : "" ?>
        <?= isset($bid) ? "By: " . $bid->client_name . " id:" . $bid->client_id : "No bids Yet" ?>
    </p>

    <?php if ($auction->sold == "Sold") { ?>
    <p>
        <strong>Price:</strong>
        <?= $auction->price ?>
    </p>

    <?php } ?>

    <?php if (isset($client) && $client != null && $auction->sold == "Not Auctioned" && strtotime($lot->date) > strtotime(date("Y-m-d H:i:s"))) { ?>
    <form method="POST" action="/bid/<?= $auction->id ?>">
        <h2>Place Your own bid!</h2>
        <input placeholder="bid amount" type="number" min="<?= $auction->lower_est ?>" name="amount"></input>
        <input type="submit">
    </form>
    <?php } ?>
</div>