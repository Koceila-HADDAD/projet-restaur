$(document).ready(function() {
    let timeoutId;
    const $input = $('#adresse_livraison');
    const $suggestions = $('#suggestions');

    $input.on('input', function() {
        clearTimeout(timeoutId);
        const query = $(this).val();

        if (query.length < 3) {
            $suggestions.empty().removeClass('show');
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
                        $suggestions.addClass('show');
                    } else {
                        $suggestions.removeClass('show');
                    }
                },
                error: function() {
                    $suggestions.removeClass('show');
                }
            });
        }, 300);
    });

    $suggestions.on('click', '.dropdown-item', function(e) {
        e.preventDefault();
        const adresseData = $(this).data('adresse');
        $input.val(adresseData.label);
        $suggestions.removeClass('show');
        console.log('Adresse complète:', adresseData.label);
        console.log('Code postal:', adresseData.postcode);
        console.log('Ville:', adresseData.city);
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#adresse, #suggestions').length) {
            $suggestions.removeClass('show');
        }
    });

    $input.on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
    
});
