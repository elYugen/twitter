import React from 'react';
import { FaDev } from "react-icons/fa";
import { IoGameController } from "react-icons/io5";
import { LuJapaneseYen } from "react-icons/lu";
import styles from './Sidebar.module.css';

function Sidebar () {
  return(
    <>
    <div className={styles.sidebar}>
      <div className={styles.searchArticle}>
        <input type="text" placeholder="Rechercher une publication..." />
      </div>
      <div className={styles.categoriesBox}>
        <h2 className={styles.categoriesTitle}>Tendance</h2>
        <div className={styles.categoryItem}><a href="#"><FaDev /> Développement</a></div>
        <div className={styles.categoryItem}><a href="#"><LuJapaneseYen /> Anime</a></div>
        <div className={styles.categoryItem}><a href="#"><IoGameController /> Jeux Vidéos</a></div>
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
