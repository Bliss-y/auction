<h1>
    <?= isset($client) ? "Edit " . $client->name : "Add a client" ?>
</h1>
<form action="" method="POST">
    <div>
        <label>Name:</label>
        <input name="name" , type="text" placeholder="name" value="<?= $client->name ?? "" ?>" />
    </div>
    <div>
        <label>Contact Number:</label>
        <input name="contact" , type="text" placeholder="contact number" value="<?= $client->contact ?? "" ?>" />
    </div>
    <div>
        <label>email:</label>
        <input name="email" , type="email" placeholder="email" value="<?= $client->email ?? "" ?>" />
    </div>
    <div>
        <label>address:</label>
        <input name="address" , type="text" placeholder="address" value="<?= $client->address ?? "" ?>" />
    </div>
    <div>
        <label>approved:</label>
        <select name="approved">
            <option value="0" <?= isset($client) && $client->approved == 0 ? " selected" : "" ?>>No</option>
            <option value="1" <?= isset($client) && $client->approved == 0 ? " selected" : "" ?>>Yes</option>
        </select>
    </div>
    <div>
        <label>client type:</label>
        <select name="type">
            <option value="0">Buyer</option>
            <option value="1">Seller</option>
            <option value="2">Joint</option>
        </select>
    </div>
    <div>
        <label>bank:</label>
        <input name="bank" , type="text" placeholder="bank" value="<?= $client->bank ?? "" ?>" />
    </div>
    <div>
        <label>bank Sort Code:</label>
        <input name="bank_sort" , type="text" placeholder="bank sort code" value="<?= $client->bank_sort ?? "" ?>" />
    </div>
    <input type="submit" />
</form>