<?php foreach($table_list as $table)  { ?>
    <div class="panel">
        <div class="panel-header item item-table" onclick="Vers.toggleBody(this);" data-checksum="<?php echo md5($table['name']); ?>">
            <span><i class="fa fa-check-circle-o" aria-hidden="true"></i></span> <?php echo $table['name']; ?> <span class="error-count"></span>
        </div>
        <!--/.panel-header-->

        <div class="panel-body">
            <div class="panel-section" onclick="Vers.toggleAttribute(this);"><span></span>Attribute</div>
            <!--/.panel-section-->

            <table class="panel-table" id="attributeTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Key</th>
                        <th>Null</th>
                        <th>Default</th>
                        <th>Extra</th>
                    </tr>
                </thead>
                <tbody id="attribute-body">
                    <?php foreach($table['attribute'] as $ind => $attribute) { ?>
                        <tr>
                            <td class="center"><?php echo ($ind + 1); ?></td>
                            <td data-checksum="<?php echo md5($table['name'].'attribute'.$attribute->Field); ?>" class="item"><?php echo $attribute->Field; ?></td>
                            <td data-checksum="<?php echo md5($table['name'].'attribute'.$attribute->Type); ?>" class="item"><?php echo $attribute->Type; ?></td>
                            <td data-checksum="<?php echo md5($table['name'].'attribute'.$attribute->Key); ?>" class="center item"><?php echo $attribute->Key; ?></td>
                            <td data-checksum="<?php echo md5($table['name'].'attribute'.$attribute->Null); ?>" class="center item"><?php echo $attribute->Null; ?></td>
                            <td data-checksum="<?php echo md5($table['name'].'attribute'.$attribute->Default); ?>" class="center item"><?php echo $attribute->Default; ?></td>
                            <td data-checksum="<?php echo md5($table['name'].'attribute'.$attribute->Extra); ?>" class="item"><?php echo $attribute->Extra; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!--/.panel-table-->

            <div class="panel-section" onclick="Vers.toggleIndex(this);"><span></span>Index</div>
            <!--/.panel-section-->

            <table class="panel-table" id="indexTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Keyname</th>
                        <th>Type</th>
                        <th>Packed</th>
                        <th>Unique</th>
                        <th>Column</th>
                        <th>Cardinality</th>
                        <th>Collation</th>
                        <th>Null</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody id="attribute-body">
                    <?php $indc = 1; ?>
                    <?php foreach($index_list as $keyname => $index) { ?>
                        <?php foreach($index['column'] as $ind => $column) { ?>
                            <tr>
                                <?php if ($ind == 0) { ?>
                                    <td rowspan="<?php echo count($index['column']); ?>" class="center">
                                        <?php echo $indc++; ?>
                                    </td>
                                    <td data-checksum="<?php echo md5($table['name'].'index'.$keyname); ?>" rowspan="<?php echo count($index['column']); ?>" class="item">
                                        <?php echo $keyname; ?>
                                    </td>
                                    <td data-checksum="<?php echo md5($table['name'].'index'.$index['type']); ?>" rowspan="<?php echo count($index['column']); ?>" class="center item">
                                        <?php echo $index['type']; ?>
                                    </td>
                                    <td data-checksum="<?php echo md5($table['name'].'index'.$index['packed']); ?>" rowspan="<?php echo count($index['column']); ?>" class="center item">
                                        <?php echo $index['packed']; ?>
                                    </td>
                                    <td data-checksum="<?php echo md5($table['name'].'index'.$index['unique']); ?>" rowspan="<?php echo count($index['column']); ?>" class="center item">
                                        <?php echo $index['unique']; ?>
                                    </td>
                                <?php } ?>
                                <td data-checksum="<?php echo md5($table['name'].'index'.$column['name']); ?>" class="item"><?php echo $column['name']; ?></td>
                                <td data-checksum="<?php echo md5($table['name'].'index'.$column['cardinality']); ?>" class="center"><?php echo $column['cardinality']; ?></td>
                                <td data-checksum="<?php echo md5($table['name'].'index'.$column['collation']); ?>" class="center item"><?php echo $column['collation']; ?></td>
                                <td data-checksum="<?php echo md5($table['name'].'index'.$column['null']); ?>" class="center item"><?php echo $column['null']; ?></td>
                                <td data-checksum="<?php echo md5($table['name'].'index'.$column['comment']); ?>" class="item"><?php echo $column['comment']; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
            <!--/.panel-table-->
        </div>
        <!--/.panel-body-->
    </div>
    <!--/.panel-->
<?php } ?>
