<style>

    .background-image.bg-image {
        position: relative;
        width: 100%;
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
        display: block;
    }

    [data-parallax] .background-image-holder.parallax {
        background-attachment: fixed;
    }

</style>

<div class="container">
    <div class="row background-image bg-image text-center rounded text-white"
         style="background-image: url('<?php print elements_url() ?>images/image-for-layouts.png'); height:100vh;">
        <div class="h-100 d-flex align-items-center element">
            <div class="col-12">
                <h1 class="pt-3 mb-3">Our Services</h1>
                <p class="mb-3">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </p>
                <module type="btn" text="Button" />
            </div>
        </div>
    </div>
</div>
