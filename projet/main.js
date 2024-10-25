function mettreAJourCompteurPanier() {
    // recup nbr d'article dans le panier via requête AJAX
    fetch('panier_count.php')
        .then(response => response.json()) // traitement reponse JSON
        .then(data => {
            const panierCompteur = document.getElementById('panier-compteur');
            if (panierCompteur) {
                panierCompteur.textContent = data.count; // maj du compteur
            }
        })
        .catch(error => console.error('Erreur:', error)); 
}

// ajout d'evenement pour mettre a jour le compteur au chargement de la page
document.addEventListener('DOMContentLoaded', mettreAJourCompteurPanier);
