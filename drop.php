<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .position-relative {
            position: relative;
        }

        .custom-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem); /* Juste en dessous de l'input */
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1000;
            display: none; /* Caché par défaut */
            background-color: #007bff; /* bg-primary */
            border: 1px solid #0056b3;
            border-radius: 0.25rem;
            padding: 0;
            overflow-y: auto;
            max-height: 200px;
        }

        .custom-dropdown.show {
            display: block !important; /* Affiche quand actif */
        }

        .custom-dropdown .dropdown-item {
            color: white; /* Texte blanc sur fond bleu */
            padding: 0.5rem 1rem;
        }

        .custom-dropdown .dropdown-item:hover {
            background-color: #0056b3; /* Couleur de survol */
        }
    </style>
</head>
<body>
    <div class="container mt-5 bg-light">
        <h1>Remplir le formulaire</h1>
        <form method="POST" action="" class="mt-4">
            <!-- Autres champs -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="adresse" class="form-label">Adresse</label>
                </div>
                <div class="col-md-8">
                    <div class="position-relative">
                        <input type="text" class="form-control" id="adresse" name="adresse_livraison" placeholder="Entrez votre adresse" autocomplete="off">
                        <ul class="dropdown-menu custom-dropdown" id="suggestions"></ul>
                    </div>
                </div>
            </div>
            <!-- Autres champs -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success w-45">Payer</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            let timeoutId;
            const $input = $('#adresse');
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
    </script>
</body>
</html>