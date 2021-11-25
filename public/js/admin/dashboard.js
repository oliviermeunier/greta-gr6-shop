async function onClickDeleteButton(event)
{
    // On arrête le comportement par défaut du navigateur (changer de page au click sur un lien)
    event.preventDefault();

    // Popup de confirmation
    const confirmation = window.confirm('$etes-vous certain de vouloir supprimer ce produit ?');

    if (confirmation) {

        // On récupère dans l'attribut href du lien cliqué l'URL de la requête AJAX
        const url = event.currentTarget.href;

        // Ajout de l'entête X-Requested-With pour préciser qu'il s'agit d'une requête AJAX
        const options = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        // Envoi de la requête AJAX
        const response = await fetch(url, options);
        
        // On récupère en réponse l'id du produit supprimé
        const deletedProductId = await response.json();
    
        // Ce qui nous permet de sélectionner la ligne à effacer grâce aux id ajoutés dans les <tr>
        const row = document.getElementById(`product-${deletedProductId}`);
    
        if (row != null) {
            row.remove();
        }
    }
}

// Sélection des boutons de suppression
const deleteButtons = document.querySelectorAll('.btn-delete');

for (const deleteButton of deleteButtons) {

    // Installation du gestionnaire d'événement au click sur chaque bouton "supprimer"
    deleteButton.addEventListener('click', onClickDeleteButton);
}