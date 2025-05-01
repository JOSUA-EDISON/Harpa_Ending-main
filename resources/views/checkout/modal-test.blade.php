@extends('layouts.app')

@section('title', 'Modal Test')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Modal Testing Page</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Test Modal Opening</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Test Button 1: Standard Bootstrap Modal -->
                        <div class="mb-4">
                            <h5>Test 1: Standard Bootstrap Modal</h5>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#testModal">
                                Open Standard Modal
                            </button>
                        </div>

                        <!-- Test Button 2: JavaScript Open -->
                        <div class="mb-4">
                            <h5>Test 2: JavaScript Modal Open</h5>
                            <button type="button" class="btn btn-success" id="jsOpenModalBtn">
                                Open Via JavaScript
                            </button>
                        </div>

                        <!-- Test Button 3: Location Selector -->
                        <div class="mb-4">
                            <h5>Test 3: Location Selector Modal</h5>
                            <button type="button" class="btn btn-info" id="openLocationSelectorBtn">
                                Open Location Selector
                            </button>
                        </div>

                        <!-- Test Results Area -->
                        <div class="alert alert-secondary mt-4">
                            <h6>Debug Info:</h6>
                            <div id="debugInfo">Click buttons to test modal functionality</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Standard Test Modal -->
<div class="modal fade" id="testModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                This is a standard Bootstrap modal for testing.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include the location selector component -->
@include('cart.province-city-selector')
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Log when the page is ready
    console.log('Modal test page loaded');

    // Verify jQuery is working
    if (window.jQuery) {
        console.log('jQuery is loaded: version ' + jQuery.fn.jquery);
    } else {
        console.log('jQuery is NOT loaded!');
    }

    // Verify Bootstrap is working
    if (typeof $().modal === 'function') {
        console.log('Bootstrap modal is available');
    } else {
        console.log('Bootstrap modal is NOT available!');
    }

    // Add event listener to the JS button
    document.getElementById('jsOpenModalBtn').addEventListener('click', function() {
        console.log('JS button clicked');
        $('#testModal').modal('show');

        // Update debug info
        document.getElementById('debugInfo').innerHTML = 'JS button clicked at ' + new Date().toLocaleTimeString();
    });

    // Add event listener to the location selector button
    document.getElementById('openLocationSelectorBtn').addEventListener('click', function() {
        console.log('Location selector button clicked');

        // Try multiple approaches
        try {
            // 1. Standard Bootstrap way
            $('#locationSelectorModal').modal('show');

            // 2. Backup direct jQuery way
            setTimeout(function() {
                if (!$('#locationSelectorModal').hasClass('show')) {
                    $('#locationSelectorModal').addClass('show').css('display', 'block');
                    $('body').addClass('modal-open').append('<div class="modal-backdrop fade show"></div>');
                }
            }, 100);

            // Update debug info
            document.getElementById('debugInfo').innerHTML = 'Location button clicked at ' + new Date().toLocaleTimeString();
        } catch (e) {
            console.error('Error showing modal:', e);
            document.getElementById('debugInfo').innerHTML = 'Error: ' + e.message;
        }
    });

    // Check if modals exist in the DOM
    console.log('Test modal exists:', $('#testModal').length > 0);
    console.log('Location modal exists:', $('#locationSelectorModal').length > 0);
});
</script>
@endpush
