<form action="browse.php" method="post" role="form">
    <input type="hidden" name="valid" value="<?php echo $USER->key; ?>" />
    <span>
        <label>Asset Tag</label>
        <input type="text" id="asset_tag" name="asset_tag" value="<?php echo $asset_tag; ?>" class=".form-control" />
        <button type="submit" value="" id="nav-search-button" class="btn btn-default" aria-label="Left Align">
            <span style="font-size:16px" class="glyphicon glyphicon-arrow-right"></span>
        </button>
    </span>
</form>
