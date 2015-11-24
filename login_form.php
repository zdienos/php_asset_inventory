    <form method="POST">
        <input type="hidden" name="key" value="<?php echo base64_encode(session_id()); ?>"/> 
        <p><label for="username">Username: </label><input id="username" type="text" name="username" /></p>
        <p><label for="password">Password: </label><input id="password" type="password" name="password" /></p>
        <p><input type="submit" name="submit" value="Submit" /></p>
    </form>