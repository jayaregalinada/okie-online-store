<div class="modal-body item-modal"
    ng-swipe-left="Lightbox.nextImage()"
    ng-swipe-right="Lightbox.prevImage()" ng-controller="ItemController">
    <div class="modal-header">
        <button type="button" class="close" ng-click="Lightbox.closeModal()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{# item.name #}</h4>
    </div>
    <div class="lightbox-image-container">
        <img lightbox-src="{# Lightbox.imageUrl #}" alt="" style="width:100%" class="img-responsive" />
    </div>
    
    <div class="modal-footer">
        <nav class="lightbox-navigation">
            <ul class="pager">
                <li>
                    <a href="javascript:void(0);" ng-click="Lightbox.prevImage()"><i class="fa fa-angle-left fa-2x"></i></a>
                </li>
                <li>
                    <a href="javascript:void(0);" ng-click="Lightbox.nextImage()"><i class="fa fa-angle-right fa-2x"></i></a>
                </li>
            </ul>
        </nav>
    </div>

    

</div>
