<script>
    //-------------------------------------------------------------------------
    // 1. Loading the livewire Toast
    //-------------------------------------------------------------------------
    document.addEventListener("livewire:init", function () {
//-------------------------------------------------------------------------
// Loading the livewire Toast
//-------------------------------------------------------------------------
        Livewire.on("showToast", function () {
            const toastLiveExample = document.getElementById("liveToast");
            const toast = new bootstrap.Toast(toastLiveExample);
            toast.show();
        });

        Livewire.on("load", function () {

            $(".date-picker").datepicker();
            console.log('Livewire component has finished loading');

        });





        Livewire.hook('element.init', ({component, el}) => {
        })


//-------------------------------------------------------------------------
// ## Closing modals
//-------------------------------------------------------------------------

        Livewire.on("showModal", (params = null) => {
            console.log("Modal ID to show:", params.modelId);

            let modal_id = String(params.modelId);

            if (typeof modal_id === "string") {
                $("#" + modal_id).modal("show");
            } else {
                console.log("Else run Modal ID to show:", modal_id);
            }

        });

        Livewire.on("showOffcanvas", (params = null) => {
            console.log("Modal ID to show:", params.modelId);

            let modal_id = String(params.modelId);

            if (typeof modal_id === "string") {
                $("#" + modal_id).Offcanvas("show");
            } else {
                console.log("Else run Offcanvas ID to show:", modal_id);
            }
        });

        Livewire.on("hideOffcanvas", (params = {}) => {
            if (
                params &&
                typeof params === "object" &&
                params.modalId &&
                typeof params.modalId === "string"
            ) {
                console.log("Modal ID to hide:", params.modalId);
                $("#" + params.modalId).Offcanvas("hide");
            } else {

                $(".offcanvas").Offcanvas("hide");
            }
        });

        });
    });


    //-------------------------------------------------------------------------
    //2. Expandable images
    //-------------------------------------------------------------------------
    // MOVED TO FUSION>SCRIPTS>EXPANDING-IMAGES-JS
</script>
