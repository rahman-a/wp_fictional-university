<form class="search-form" method="GET" action="<?= esc_url(site_url("/")) ?>">
    <label class="headline headline--medium" for="search">Perform a New Search</label>
    <div class="search-form-row">
        <input placeholder="What are you looking for?" class="s" id="search" name="s" type="text">
        <input class="search-submit" type="submit" value="Search">
    </div>
</form>