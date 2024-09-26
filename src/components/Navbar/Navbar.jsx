import React, { useEffect, useState } from 'react';
import styles from './Navbar.module.css';
import { IoMdHome } from "react-icons/io";
import { FaDev } from "react-icons/fa";
import { IoGameController } from "react-icons/io5";
import { LuJapaneseYen } from "react-icons/lu";
import { GiRaven } from "react-icons/gi";
import { FaMagnifyingGlass } from "react-icons/fa6";
import Logo from "../../ui/logo";
import axios from "axios";
import ModalCreatePost from "../ModalCreatePost/ModalCreatePost";

function Navbar () {
  const [session, setSession] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  useEffect(() => {
    axios.get("http://localhost/twitter/backend/controller/Session.php", { withCredentials: true })
      .then(response => {
        setSession(response.data);
      })
      .catch(error => {
        console.log("Erreur lors de la récupération de la session:", error);
      });
  }, []);

  const handleLogout = () => {
    axios.post("http://localhost/twitter/backend/controller/UserLogout.php", {}, { withCredentials: true })
      .then(response => {
        if (response.data.success) {
          setSession(null);
        }
      })
      .catch(error => {
        console.log("Erreur lors de la déconnexion:", error);
      });
  };

  const openModal = () => {
    setIsModalOpen(true);
  };

  const closeModal = () => {
    setIsModalOpen(false);
  };

  return (
    <>
      <div className={styles.navbar}>
        <div className={styles.navbarLogo}>
          <Logo />
        </div>
        <ul className={styles.navbarItem}>
          <li className={styles.navbarLink}><a href="/home"><IoMdHome /> Accueil</a></li>
          <li className={styles.navbarLink}><a href="/explore"><FaMagnifyingGlass /> Explorer</a></li>
          {session ? (
            <>
          <div className={styles.navbarLinkProfil}>
            <li className={styles.navbarLink}><a href={`/profile/${session.id}`}><GiRaven /> Profil</a></li>
          </div>
      
              <button onClick={openModal} className="button" style={{ fontSize: "20px" }}>Poster</button>
              <div className={styles.navbarUser}>
                <a href={`/profile/${session.id}`}><img src={session.pictures} alt="user" /></a>
                <div className={styles.navbarUserData}>
                  <p>{session.username}</p>
                  <p className={styles.navbarUserDataMail}>{session.email}</p>
                  <p className={styles.navbarUserDataLogout} onClick={handleLogout}>Déconnexion</p>
                </div>
              </div>
            </>
          ) : (
            <button onClick={() => window.location.href = '/login'} className="button" style={{ fontSize: "20px" }}>
              Rejoindre Wishtter
            </button>
          )}
        </ul>
      </div>
      <ModalCreatePost isOpen={isModalOpen} onClose={closeModal} />
    </>
  );
}

export default Navbar;
