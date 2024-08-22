/* ------------------------------------------------------------------------------
 *
 *  # Progress bars & loaders
 *
 *  Demo JS code for components_progress.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

const Progress = function () {


    //
    // Setup module components
    //

    // Spinner with overlay
    const _componentOverlay = function () {

        // Elements
        // Change button.getAttribute('data-icon') to your desired icon here. Current
        // config is for demo. Or use this code if you wish
        const buttonClass = 'btn-launch-spinner',
            containerClass = 'card',
            overlayClass = 'card-overlay',
            overlayAnimationClass = 'card-overlay-fadeout';

        // Configure
        document.querySelectorAll(`.${buttonClass}`).forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                // Elements
                const parentContainer = button.closest(`.${containerClass}`),
                    overlayElement = document.createElement('div'),
                    overlayElementIcon = document.createElement('span');

                // Append overlay with icon
                overlayElement.classList.add(overlayClass);
                parentContainer.appendChild(overlayElement);
                if (button.getAttribute('data-spin') == 'false') {
                    overlayElementIcon.classList.add(button.getAttribute('data-icon'));
                }
                else {
                    overlayElementIcon.classList.add(button.getAttribute('data-icon'), 'spinner');
                }
                overlayElement.appendChild(overlayElementIcon);

                // Remove overlay after 2.5s, for demo only
                /*setTimeout(function () {
                    overlayElement.classList.add(overlayAnimationClass);
                    ['animationend', 'animationcancel'].forEach(function (e) {
                        overlayElement.addEventListener(e, function () {
                            overlayElement.remove();
                        });
                    });
                }, 2500);*/
            });
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            var progress = '<div id="progress_div" class="col-md-1" style="position: fixed;z-index: 2;width: 100%;height: 100%;top:0;left:0;background-color: rgba(0,0,0,0.5);justify-content: center;color: #fff;">';
            progress += '<div style="text-align: center; width: 100%;height: 100%;margin-top: 300px;">';
            progress += '<div class="spinner-border" style="width: 80px;height: 80px;" role="status" style="text-align: center;"></div>';
            progress += '</div>';
            progress += '</div>';
            $("body").append(progress);
            _componentOverlay();
        }
    }
}();


// Initialize module
// ------------------------------




function showProgress() {
    Progress.init();
}

function hideProgress() {
    $("#progress_div").remove();
}
