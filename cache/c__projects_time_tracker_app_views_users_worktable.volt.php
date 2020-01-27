


    
    
        
    
        
    
    
    
    
        
            
        
    
        
            
        
    

    


<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100">
            <div class="table100">

                <table>
                    <thead>
                    <tr class="table100-head">
                        <th class="column1">День</th>
                        <?php foreach ($data as $fills) { ?>
                            <?php foreach ($fills as $userName) { ?>
                                <?php foreach ($userName as $item) { ?>
                                    <?php foreach ($item as $a) { ?>
                                         <th scope="column2"><?= $a ?></th>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                        
                            
                                
                                    
                                        
                                    
                                
                            
                        
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>