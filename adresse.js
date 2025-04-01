$(document).ready(function() {
    // Autocomplétion
    let timeoutId;
    const $input = $('#adresse');
    const $suggestions = $('#suggestions');

    $input.on('input', function() {
        clearTimeout(timeoutId);
        const query = $(this).val();

        if (query.length < 3 || $input.prop('disabled')) {
            $suggestions.empty().dropdown('hide');
            return;
        }

        timeoutId = setTimeout(function() {
            $.ajax({
                url: 'https://api-adresse.data.gouv.fr/search/',
                data: { q: query, limit: 5 },
                success: function(data) {
                    $suggestions.empty();
                    if (data.features.length > 0) {
                        data.features.forEach(function(feature) {
                            const adresse = feature.properties.label;
                            const $item = $('<li>')
                                .append($('<a>')
                                    .addClass('dropdown-item')
                                    .text(adresse)
                                    .attr('href', '#')
                                    .data('adresse', feature.properties)
                                );
                            $suggestions.append($item);
                        });
                        $suggestions.dropdown('show');
                    } else {
                        $suggestions.dropdown('hide');
                    }
                },
                error: function() {
                    $suggestions.dropdown('hide');
                }
            });
        }, 300);
    });

    $suggestions.on('click', '.dropdown-item', function(e) {
        e.preventDefault();
        const adresseData = $(this).data('adresse');
        $input.val(adresseData.label);
        $suggestions.dropdown('hide');
        console.log('Adresse complète:', adresseData.label);
        console.log('Code postal:', adresseData.postcode);
        console.log('Ville:', adresseData.city);
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#adresse, #suggestions').length) {
            $suggestions.dropdown('hide');
        }
    });

    $input.on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    // Désactivation du champ adresse
    const $typeLivraison = $('#type-livraison');
    function toggleAdresseInput() {
        if ($typeLivraison.val() === 'surplace') {
            $input.prop('disabled', true).val('');
        } else {
            $input.prop('disabled', false);
        }
    }
    toggleAdresseInput();
    $typeLivraison.on('change', function() {
        toggleAdresseInput();
    });
});

