<h1>
    <?= isset($auction->name) ? "Edit" . $auction->name : "Add an auction item in " . $category->name . " category" ?>
</h1>
<form action="" method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <input name="title" type="text" placeholder="Auction title" value="<?= $auction->title ?? "" ?>" />
    <label>Description:</label>
    <textarea name="description" type="text"
        placeholder="Description of the category"><?= $auction->description ?? "" ?></textarea>

    <label>artist name:</label>
    <input name="artist_name" type="text" value="<?= $auction->artist_name ?? "" ?>" />
    <label>Auction:</label>
    <select name="lot">
        <?php foreach ($lots as $b) { ?>
        <option value="<?= $b->id ?>" <?= isset($auction->lot) && $auction->lot == $b->id ? " selected" : "" ?>>
            <?= $b->title ?>
        </option>
        <?php } ?>
    </select>
    <label>seller:</label>
    <select name="seller">
        <?php foreach ($buyers as $b) { ?>
        <option value="<?= $b->id ?>" <?= isset($auction->buyer) && $auction->buyer == $b->id ? " selected" : "" ?>>
            <?= $b->name ?>
        </option>
        <?php } ?>
    </select>
    <label>higher price estimate:</label>
    <input name="higher_est" type="text" value="<?= $auction->higher_est ?? "" ?>" />

    <label>lower price estimate:</label>
    <input name="lower_est" type="text" value="<?= $auction->lower_est ?? "" ?>" />

    <label>produced Date:</label>
    <input name="date" type="text" placeholder="produced date" value="<?= $auction->date ?? "" ?>" />

    <label>Classification:</label>
    <input name="classification" type="text" placeholder="classification"
        value="<?= $auction->classification ?? "" ?>" />
    <?php
    $catarr = $category->getFieldsArray();
    for ($i = 0; $i < count($catarr); $i++) {
        ?>
    <label>
        <?= $catarr[$i] ?>:
    </label>
    <input name="<?= $catarr[$i] ?>" type="text" value="<?= $auction->category_fields[$catarr[$i]] ?? "" ?>" />

    <?php
    }
    ?>
    <label>Add an Image</label>
    <input type="file" name="file" accept=".jpg" id="file">
    <button type="submit">Submit </button>
</form>