<!-- Direct modal hotfix -->
<script>
(function() {
    console.log("🔧 Modal hotfix script loaded");

    // Execute immediately and also after DOM load
    function fixModalButton() {
        console.log("🔧 Running modal fix");

        // Try both with jQuery and vanilla JS
        try {
            // Find the button
            var btnSelector = '#openLocationSelectorBtn';
            var btnElement = document.querySelector(btnSelector);

            if (btnElement) {
                console.log("🔧 Found button element:", btnElement);

                // Remove any existing click handlers
                if (window.jQuery) {
                    jQuery(btnSelector).off('click');
                }

                // Clone and replace to remove all event handlers
                var newBtn = btnElement.cloneNode(true);
                btnElement.parentNode.replaceChild(newBtn, btnElement);

                // Add custom handler with direct modal show
                newBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log("🔧 Button clicked via hotfix");

                    var modal = document.querySelector('#locationSelectorModal');

                    if (window.jQuery && typeof jQuery().modal === 'function') {
                        console.log("🔧 Using jQuery modal");
                        jQuery('#locationSelectorModal').modal('show');
                    } else {
                        console.log("🔧 Falling back to direct DOM manipulation");
                        if (modal) {
                            modal.classList.add('show');
                            modal.style.display = 'block';
                            document.body.classList.add('modal-open');

                            // Add backdrop
                            var backdrop = document.createElement('div');
                            backdrop.classList.add('modal-backdrop', 'fade', 'show');
                            document.body.appendChild(backdrop);
                        } else {
                            console.error("🔧 Modal element not found!");
                        }
                    }

                    return false;
                });

                console.log("🔧 Modal fix applied successfully");
            } else {
                console.error("🔧 Button not found in DOM");
            }
        } catch (err) {
            console.error("🔧 Error fixing modal:", err);
        }
    }

    // Run fix immediately
    fixModalButton();

    // Also run after DOM content loaded
    document.addEventListener('DOMContentLoaded', fixModalButton);

    // And after full page load as a final attempt
    window.addEventListener('load', fixModalButton);

    // Add a direct button to the page to force reload modals in case all else fails
    setTimeout(function() {
        try {
            var fixContainer = document.createElement('div');
            fixContainer.style.position = 'fixed';
            fixContainer.style.bottom = '10px';
            fixContainer.style.right = '10px';
            fixContainer.style.zIndex = '9999';

            var fixButton = document.createElement('button');
            fixButton.innerHTML = '🔄 Fix Modals';
            fixButton.className = 'btn btn-sm btn-warning';
            fixButton.addEventListener('click', function() {
                // Force reload bootstrap JS
                var script = document.createElement('script');
                script.src = 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js';
                document.body.appendChild(script);

                // Reapply fix
                setTimeout(fixModalButton, 500);

                alert('Modal fix attempted. Please try opening the location selector now.');
            });

            fixContainer.appendChild(fixButton);
            document.body.appendChild(fixContainer);
        } catch (e) {
            console.error("Error adding fix button:", e);
        }
    }, 2000);
})();
</script>
