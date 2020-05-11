<div class="related">
    <?php if(count($listaCursos) > 0):?>
    <h4><?= __('Lista de Cursos') ?></h4>
    <table cellpadding="0" cellspacing="0" class="tableResponsiveUserList tableResponsiveUserListCourses">
        <thead>
        <tr>
            <th><?= __('Nome Curso') ?></th>
            <th><?= __('Data Início') ?></th>
            <th><?= __('Data Final') ?></th>
            <th><?= __('Status') ?></th>
        </tr>
    </thead>
        <?php foreach ($listaCursos as $curso): ?>
        <tr>
            <td>&nbsp <?= h($curso['curso']) ?></td>
            <td><?= \Cake\I18n\Time::parse($curso['timestart'])->format('d/m/Y') ?></td>
            <td><?= \Cake\I18n\Time::parse($curso['timeend'])->format('d/m/Y') ?></td>
            <td><?= h($curso['status']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
     <?php else: ?>
        <h5><?= __('Nenhum Curso QiSat') ?></h5>
    <?php endif; ?>
</div>