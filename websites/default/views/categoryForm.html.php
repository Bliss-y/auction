<h1>
    <?= isset($category) ? "Edit " . $category->name : "Add a category" ?>
</h1>
<form action="" method="POST">
    <div>
        <?= isset($category) ? '<a href="/addCategoryField/' . $category->id . '"><div> Add Fields </div> </a>' : "" ?>
    </div>
    <div>
        <label>Name:</label>
        <input name="name" , type="text" placeholder="category name" value="<?= $category->name ?? "" ?>" />
        <?php if (!isset($category)) { ?>
        <label>category fields (seperate each field with space Eg: "dimensions xyz"):</label>
        <input name="category_fields" , type="text" placeholder="Category specific fields"
            value="<?= $category->name ?? "" ?>" />
        <?php } else {
            foreach ($category->getFieldsArray() as $c) {
                ?>
        <div>
            <?= $c ?><a href="/editCategoryField/<?= $category->id ?>/<?= $c ?>"> Edit </a><a
                href="/removeField/<?= $category->id ?>/<?= $c ?>">Remove </a>
        </div>
        <?php
            }
        }
        ?>
    </div>
    <label>Description:</label>
    <textarea name="description" , type="text"
        placeholder="Description of the category"><?= $category->description ?? "" ?></textarea>
    <input type="submit" />
</form>