<h1>
    <?= isset($lot->title) ? "Edit" . $lot->title : "Add an auction" ?>
</h1>
<form action="" method="POST">
    <label>title:</label>
    <input name="title" type="text" placeholder="Auction title" value="<?= $lot->title ?? "" ?>" />

    <label>date:</label>
    <input name="date" type="date" placeholder="Auction date" value="<?= $lot->date ?? "" ?>" />
    <label>period:</label>
    <input name="period" type="text" placeholder="Auction period" value="<?= $lot->period ?? "" ?>" />
    <label>location:</label>
    <input name="location" type="text" placeholder="Auction location" value="<?= $lot->location ?? "" ?>" />
    <input type="submit" />
</form>