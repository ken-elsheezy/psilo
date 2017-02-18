<div class="row">
    <div class="col-md-9">
        {$T_TABLE}
    </div>
    <div class="col-md-3">
        <h2 class="title"> <i class="fa fa-gears"></i> Actions </h2>
        <hr/>
        <ul class="list-group">
            <li class="list-group-item {if $smarty.get.page == 'categories'}active{/if}">
                <i class="fa fa-list-alt"></i> 
                <a href="{$T_MODULE_BASEURL}&page=categories">
                    Categories 
                </a>
                    <span class="pull-right">
                        <a href="{$T_MODULE_BASEURL}&page=categories&op=add" title="Add a new entry">
                            <i class="fa fa-plus-circle"></i>
                        </a> &nbsp;
                        
                    </span>  
            </li>
            <li class="list-group-item {if $smarty.get.page == 'articles'}active{/if}">
                <i class="fa fa-paperclip"></i> 
                <a href="{$T_MODULE_BASEURL}&page=articles">Articles</a>
                <span class="pull-right">
                        <a href="{$T_MODULE_BASEURL}&page=articles&op=add" title="Add a new entry">
                            <i class="fa fa-plus-circle"></i>
                        </a> &nbsp;
                        <a href="{$T_MODULE_BASEURL}&page=articles&op=myarticles" title="Add a new entry">
                            <i class="fa fa-user" title="My Articles"></i>
                        </a>
                    </span>  
            </li>
            <li class="list-group-item {if $smarty.get.page == 'comments'}active{/if}">
                <i class="fa fa-comments"></i> 
                <a href="{$T_MODULE_BASEURL}&page=comments">Comments</a>
                     
            </li>
            <li class="list-group-item {if $smarty.get.page == 'files'}active{/if}" >
                <i class="fa fa-file"></i> 
                <a href="{$T_MODULE_BASEURL}&page=files">Files</a>
                    <span class="pull-right">
                        <a href="{$T_MODULE_BASEURL}&page=files&op=add" title="Add a new entry">
                            <i class="fa fa-plus-circle"></i>
                        </a>
                    </span>  
            </li>
            <li class="list-group-item {if $smarty.get.page == 'ratings'}active{/if}">
                <i class="fa fa-star-half-o text-danger"></i> 
                <a href="{$T_MODULE_BASEURL}&page=ratings">Ratings</a>
            </li>
        </ul>
    </div>
</div>