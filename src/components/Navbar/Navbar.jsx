import React from 'react';
import styles from './Navbar.module.css';
import { IoMdHome } from "react-icons/io";
import { FaDev } from "react-icons/fa";
import { IoGameController } from "react-icons/io5";
import { LuJapaneseYen } from "react-icons/lu";
import { GiRaven } from "react-icons/gi";
import { FaMagnifyingGlass } from "react-icons/fa6";
import Logo from "../../ui/logo"

function Navbar () {
  return(
    <>
      <div className={styles.navbar}>
        <div className={styles.navbarLogo}>
          <Logo/>
        </div>
          <ul className={styles.navbarItem}>
            <li className={styles.navbarLink}><a href="/"><IoMdHome /> Accueil</a></li>
            <li className={styles.navbarLink}><a href="#"><FaMagnifyingGlass /> Explorer</a></li>
            {/* <li className={styles.navbarLink}><a href="#"><FaDev /> Dév</a></li>
            <li className={styles.navbarLink}><a href="#"><LuJapaneseYen /> Anime</a></li>
            <li className={styles.navbarLink}><a href="#"><IoGameController /> Games</a></li> */}
            <div className={styles.navbarLinkProfil}> {/*Bouton profil qui sera invisible en desktop mais visible en mobile*/}
              <li className={styles.navbarLink}><a href="profile"><GiRaven /> Profil</a></li>
            </div>
            <button type="submit" name="login" style={{fontSize: "20px"}} className="button">Poster</button>
            <div className={styles.navbarUser}>
                <img src="https://i.pravatar.cc/300" alt="user" />
                <div className={styles.navbarUserData}>
                    <p>Username</p>
                    <p className={styles.navbarUserDataMail}>Mail Adress</p>
                </div>
            </div>
          </ul>
        </div>
    </>
  );
}

export default Navbar;
