 <h3 class="text-center">Poluplar Items</h3>
<?php
    $tranQ = $conn->query("SELECT * FROM cart WHERE paid = 1 ORDER BY id DESC LIMIT 5");
    $result = array();
    while($row = mysqli_fetch_assoc($tranQ)){
        $result[]=$row;
    }
    $row_count = $tranQ->num_rows;
    $used_id = array();
    for($i = 0; $i < $row_count; $i++){
        $json_item = $result[$i]['items'];
        $items = json_decode($json_item, true);
        foreach ($items as $item) {
            if (!in_array($item['id'], $used_id)) {
                $used_id[] = $item['id'];
            }
        }
    }
?>
<div id="recent_widget">
    <table class="table table-condensed">
         <?php 
                foreach($used_id as $id):
                    $productQ = $conn->query("SELECT id, title FROM product WHERE id ='{$id}'");
                    $product = mysqli_fetch_assoc($productQ);
                
            ?>
            <tr>
                    <td><?=substr($product['title'],0,15);?></td>
                    <td><a class="text-primary" onclick="detailsmodal('<?=$id;?>')">View</a></td>
            </tr>
        <?php endforeach; ?> 
    </table>
</div>