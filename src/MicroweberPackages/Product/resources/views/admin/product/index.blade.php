<livewire:admin-products-list />
<script>
    document.body.addEventListener("click", function(e) {
        if (!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['js-filter-item-dropdown'])) {

            var elementTarget = e.target;
            var clickedDropdownId = $(elementTarget).attr('wire:id');

            console.log(elementTarget);

           /* $('.js-filter-item-dropdown').each(function (item, element) {
                if ($(element).attr('data-dropdown-show') == '1') {
                    console.log(clickedDropdownId);
                    //  window.livewire.emit('closeDropdown','1');
                }
            });*/
        }
    });
</script>
