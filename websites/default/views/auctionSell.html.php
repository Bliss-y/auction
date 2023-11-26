<h1> Sell Auction
    <?= $auction->title ?>
</h1>


<?php if (isset($bid)) { ?>

    <p>
    <h2>Bids: </h2>
    <ul>
        <?php if (count($bid) == 0) {
            echo "no commision bids";
        } ?>
        <?php foreach ($bid as $b) { ?>
            <li>Client:
                <?= $b->client ?>, bid:£
                <?= $b->amount ?>
            </li>
        <?php } ?>
    </ul>
    </p>
<?php } ?>

<form action="" method="POST">
    <label>approved:</label>
    <select name="sold">
        <option value="Not Auctioned" <?= $auction->sold == "Not Auctioned" ? " selected" : "" ?>>
            Not Auctioned</option>
        <option value="Not Sold" <?= $auction->sold == "Not Sold" ? " selected" : "" ?>>Not Sold</option>
        <option value="Sold" <?= $auction->sold == "Sold" ? "selected" : "" ?>>Sold</option>
    </select>
    <label>buyer:</label>
    <select name="buyer">
        <?php foreach ($buyers as $b) {
            if ($b->id == $auction->seller)
                continue; ?>
            <option value="<?= $b->id ?>" <?= isset($auction->buyer) && $auction->buyer == $b->id ? " selected" : "" ?>>
                <?= $b->name ?>
            </option>
        <?php } ?>
    </select>

    <label>price:</label>
    <input name="price" type="text" placeholder="selling price" value="<?= $auction->price ?? "" ?>" />
    <label>comment:</label>
    <textarea name="comment" value=""><?= $auction->comment ?? "" ?> </textarea>
    <input type="submit" />
</form>