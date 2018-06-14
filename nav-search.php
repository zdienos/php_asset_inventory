<form action="browse.php" method="post" role="form">
    <div class="form-group">
        <input type="hidden" name="valid" value="<?php echo $USER->key; ?>" />
        <span>
            <label>Asset Tag</label>
            <input type="text" id="asset_tag" name="asset_tag" value="<?php echo $asset_tag; ?>" class=".form-control" />
            <input type="submit" value="->">
        </span>
    </div>
</form>
