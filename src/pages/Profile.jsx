import Navbar from "../components/Navbar/Navbar"
import Sidebar from "../components/Sidebar/Sidebar"
import { FaRegCalendarAlt } from "react-icons/fa";
import { IoChatbubbleOutline } from "react-icons/io5";
import axios from "axios";
import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import styles from '../components/Feed/Feed.module.css';

function Home() {
    const [session, setSession] = useState(null);
    const [userData, setUserData] = useState(null);
    const [userPosts, setUserPosts] = useState([]);
    const { id } = useParams(); 

    useEffect(() => {
        // Session utilisateur
        axios.get("http://localhost/twitter/backend/session.php", { withCredentials: true })
        .then(response => {
            setSession(response.data);
        })
        .catch(error => {
            console.log("Erreur lors de la récupération de la session:", error);
        });

        // Données de l'utilisateur spécifique
        if (id) {
            axios.get(`http://localhost/twitter/backend/getUserData.php?id=${id}`, { withCredentials: true })
            .then(response => {
                setUserData(response.data);
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des données utilisateur:", error);
            });

            // Récupération des posts de l'utilisateur
            axios.get(`http://localhost/twitter/backend/getAllPostsFromUser.php?id=${id}`, { withCredentials: true })
            .then(response => {
                setUserPosts(response.data);
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des posts:", error);
            });
        }
    }, [id]);

    const profileData = userData || session;

    return (
        <>
        <div className="layout">
            <Navbar /> 
            <div className="container">
                {profileData && (
                <div className="profileBox">
                    <div className="banner">
                        <img src={profileData.pictures} alt="user" className="profilePicture"/>
                    </div>
                    <div className="userInfo">
                        <h2 className="username">{profileData.username}</h2>
                        <p className="email">{profileData.email}</p>
                        <p className="joinDate">
                            <FaRegCalendarAlt />
                            Membre depuis le {profileData.created_at}
                        </p>
                    </div>
                    <div className="postsSection">
                        <span className="postsTitle">Publications</span>
                    </div>
                </div>
                )}
                {/* Affichage des publications de l'utilisateur */}
                {userPosts.length > 0 ? (
                    userPosts.map((post, index) => (
                        <div key={index} className={styles.postBox}>
                            <div className={styles.postHeader}>
                                <a href={`/profile/${post.author_id}`}>
                                    <img src={post.pictures} alt="user" className={styles.profilePicture} />
                                </a>
                                <div className={styles.userInfo}>
                                    <a href={`/profile/${post.author_id}`}>
                                        <span className={styles.userInfoUsername}>{post.username}</span>
                                    </a>
                                    <span className={styles.userInfoPublishedAt}>{post.publishdate}</span>
                                </div>
                            </div>
                            <div className={styles.postContent}>
                                <p>{post.content}</p>
                                {post.image_id && <img src={post.image} alt="post" className={styles.postContentImage} />}
                            </div>
                            <div className={styles.postIcon}>
                                <a href="#"><IoChatbubbleOutline /> {post.comment_count}</a>
                            </div>
                        </div>
                    ))
                ) : (
                    <p>Aucune publication trouvée.</p>
                )}
            </div>
            <Sidebar />
        </div>
        </>
    );
}

export default Home;