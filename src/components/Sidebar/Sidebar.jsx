import axios from "axios";
import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { FaDev } from "react-icons/fa";
import { IoGameController } from "react-icons/io5";
import { LuJapaneseYen } from "react-icons/lu";
import styles from './Sidebar.module.css';

function Sidebar () {
  const [categories, setCategories] = useState([]);
  
  useEffect(() => {
    // récupérations des catégories
    axios.get("http://localhost/twitter/backend/getAllCategories.php", { withCredentials: true })
    .then(response => {
        setCategories(response.data);
    })
    .catch(error => {
        console.log("Erreur lors de la récupération des catégoris:", error);
    });
  }, []);

  return(
    <>
    <div className={styles.sidebar}>
      <div className={styles.searchArticle}>
        <input type="text" placeholder="Rechercher une publication..." />
      </div>
        <div className={styles.categoriesBox}>
          <h2 className={styles.categoriesTitle}>Tendance</h2>
          {categories.length > 0 ? (
            categories.map((categ) => (
              <div key={categ.id} className={styles.categoryItem}>
                <a href="#">{categ.nom}</a>
              </div>
            ))
          ) : (
            <div className={styles.categoryItem}>Aucune catégorie trouvée</div>
          )}
        </div>
      <br />
      {/* Footer ---------- */}
      <div className={styles.footerBox}>
        <p className={styles.footerContent}>© 2024 Wishtter, Inc. <br />Aucun droit réservé</p>
      </div>
    </div>
    </>
  );
}

export default Sidebar;