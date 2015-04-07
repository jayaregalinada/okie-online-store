<div ng-controller="ImageController">
    <div class="modal-body"
        ng-swipe-left="Lightbox.nextImage()"
        ng-swipe-right="Lightbox.prevImage()">

        <div class="lightbox-image-container">
            <img lightbox-src="{# Lightbox.imageUrl #}" alt="" style="width:100%" class="img-responsive" />
        </div>
        
        <nav class="lightbox-navigation">
            <ul class="pager">
                <li>
                    <a href="#" ng-click="Lightbox.prevImage()"><i class="fa fa-angle-left fa-2x"></i></a>
                </li>
                <li>
                    <a href="#" ng-click="Lightbox.nextImage()"><i class="fa fa-angle-right fa-2x"></i></a>
                </li>
            </ul>
        </nav>

        

    </div>

    <div class="modal-footer">
        <button class="btn btn-success btn-sm" ng-click="setAsPrimary( Lightbox.index )">SET AS PRIMARY</button>
        <button class="btn btn-danger btn-sm" ng-click="deleteImage( Lightbox.index )">DELETE</button>
    </div>

</div>
