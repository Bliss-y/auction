<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/jokes.css">
    <title>
        <?= $title ?? "Fotheby's auction" ?>
    </title>
</head>

<body>
    <nav>
        <header>
            <h1>FotheBy's Auction House</h1>
        </header>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/search/advanced">Search</a></li>
            <?php if (isset($_SESSION['loggedIn'])) {
                if ($_SESSION['admin'] == 1) { ?>

                    <li><a href="/addCategory">Add Category</a></li>
                    <li><a href="/admin/categories">Manage Categories</a></li>
                    <li><a href="/admin/auctions">Manage Auction items</a></li>
                    <li><a href="/admin/lots">Manage Auctions</a></li>
                    <li><a href="/admin/users">Manage Clients</a></li>
                    <li><a href="/logout">logout </a></li>
                <?php } else { ?>
                    <li><a href="/purchases/<?= $_SESSION['client'] ?>"> My Purchases</a></li>
                    <li><a href="/sales/<?= $_SESSION['client'] ?>"> My Sales</a></li>
                    <li><a href="/logout">logout </a></li>
                <?php }
            } else { ?>
                <li><a href="/login"> Login</a></li>
            <?php } ?>
        </ul>
    </nav>

    <main id="main">
        <div id="search-container" style="margin-left: auto; margin-right: auto; width: fit-content;">
            <input type="search" id="search" style="margin-left: auto; margin-right: auto;" placeholder="search"
                autocomplete="off">
        </div>
        <hr>
        <?= $output ?>
    </main>

    <footer>
        &copy; IJDB 2018
    </footer>
</body>
<script>
    const maine = document.getElementById('main');
    maine.onclick = (e) => {
        el = document.getElementById('resholder');
        if (el) {
            el.remove();
        }
    }

    function main() {

    }
    const searchInput = document.getElementById('search');
    const searchcontainer = document.getElementById('search-container');
    searchcontainer.style.position = "relative";
    searchInput.oninput =
        async (e) => {
            const res = await fetch("/search?" + new URLSearchParams({
                q: e.target.value
            }));

            const jsonres = await res.json();
            if (jsonres.results && jsonres.results.length > 0) {
                const prevholder = document.getElementById('resholder');
                if (prevholder) {
                    prevholder.remove();
                }
                const resultHolder = document.createElement('div');
                resultHolder.style.position = "absolute";
                resultHolder.style.backgroundColor = "#443a5c";
                resultHolder.style.display = "flex";
                resultHolder.style.flexDirection = "column";
                resultHolder.style.padding = "4px";
                resultHolder.style.textAlign = "center";
                resultHolder.style.marginLeft = "auto";
                resultHolder.id = "resholder"
                for (let i of jsonres.results) {
                    const resultitem = document.createElement('a');
                    resultitem.setAttribute("href", "/catalogue/" +
                        i.id);
                    resultitem.innerText = i.title;
                    resultitem.style.color = "white";
                    resultHolder.style.width = "fit-content"
                    resultHolder.appendChild(resultitem);
                }
                searchcontainer.appendChild(resultHolder);
            }
        }

    main();
</script>

</html>