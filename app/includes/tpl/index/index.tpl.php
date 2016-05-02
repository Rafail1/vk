<?php
echo 'ss';
foreach($this->data['projects'] as $project) {
    echo "<a href='?project={$project['id']}'>{$project['name']}</a>";
}