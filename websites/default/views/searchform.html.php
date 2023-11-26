<h1>Advanced search</h1>

<form method="POST">

    <label>Name:</label>
    <input name="title" type="text" placeholder="Auction title" value="<?= $auction->title ?? "" ?>" />

    <label>artist name:</label>
    <input name="artist_name" type="text" value="<?= $auction->artist_name ?? "" ?>" />
    <label>produced Date:</label>
    <input name="date" type="text" placeholder="produced date" value="<?= $auction->date ?? "" ?>" />

    <label>price:</label>
    <input name="price" type="text" placeholder="produced date" value="<?= $auction->date ?? "" ?>" />
    <label>Category </label>
    <select name="category">
        <option value="">Any</option>
        <?php
        use http\Model\Category;

        $cs = Category::getCategories();
        foreach ($cs as $c) {
            ?>
            <option value="<?= $c->id ?>">
                <?= $c->name ?>
            </option>
            <?php
        } ?>
    </select>

    <label>Classification:</label>
    <input name="classification" type="text" placeholder="classification"
        value="<?= $auction->classification ?? "" ?>" />
    <input type="submit">
</form>
<?php if (isset($results)) { ?>
    <h2>Search Results</h2>

    <?php foreach ($results as $r) { ?>
        <p>
        <h3>
            <a href="/catalogue/<?= $r->id ?>">
                <?= $r->title ?>
            </a>
        </h3>
        <?= $r->description ?>
        </p>
        <hr>
    <?php }
} ?>