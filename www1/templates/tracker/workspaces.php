<?php if ($workspaces): ?>
    <?php foreach ($workspaces['data'] as $workspace): ?>
        <button class="btn btn-lg btn-default workspace" data-id="<?php echo $workspace['id']; ?>"><?php echo $workspace['name']; ?></button>
    <?php endforeach; ?>
<?php endif; ?>