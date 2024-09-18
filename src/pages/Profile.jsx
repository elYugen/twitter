import Navbar from "../components/Navbar/Navbar"
import Sidebar from "../components/Sidebar/Sidebar"
import { FaRegCalendarAlt, FaTrash } from "react-icons/fa";
import { IoChatbubbleOutline } from "react-icons/io5";
import axios from "axios";
import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import styles from '../components/Feed/Feed.module.css';
import { FaRegTrashAlt } from "react-icons/fa";
import { FaRegEdit } from "react-icons/fa";

function Home() {
    const [session, setSession] = useState(null);
    const [userData, setUserData] = useState(null);
    const [userPosts, setUserPosts] = useState([]);
    const [editingPost, setEditingPost] = useState(null);
    const { id } = useParams();
    const navigate = useNavigate();

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

            fetchUserPosts();
        }
    }, [id]);

    const fetchUserPosts = () => {
        axios.get(`http://localhost/twitter/backend/getAllPostsFromUser.php?id=${id}`, { withCredentials: true })
        .then(response => {
            setUserPosts(response.data);
        })
        .catch(error => {
            console.error("Erreur lors de la récupération des posts:", error);
        });
    };

    const deletePost = async (postId) => {
        if (window.confirm("Êtes-vous sûr de vouloir supprimer cette publication ?")) {
            try {
                const response = await axios.post('http://localhost/twitter/backend/deletePostOnProfile.php', 
                    { post_id: postId },
                    { withCredentials: true }
                );
                if (response.data.success) {
                    fetchUserPosts();
                } else {
                    alert(response.data.message);
                }
            } catch (error) {
                console.error("Erreur lors de la suppression de la publication:", error);
                alert("Une erreur est survenue lors de la suppression de la publication.");
            }
        }
    };

    const startEditing = (post) => {
        setEditingPost(post);
    };

    const cancelEditing = () => {
        setEditingPost(null);
    };

    const saveEdit = async (postId, newContent) => {
        try {
            const response = await axios.post('http://localhost/twitter/backend/editPostOnProfile.php', 
                { post_id: postId, content: newContent },
                { withCredentials: true }
            );
            if (response.data.success) {
                setUserPosts(userPosts.map(post => 
                    post.id === postId ? { ...post, content: newContent } : post
                ));
                setEditingPost(null);
            } else {
                alert(response.data.message);
            }
        } catch (error) {
            console.error("Erreur lors de la mise à jour de la publication:", error);
            alert("Une erreur est survenue lors de la mise à jour de la publication.");
        }
    };

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
                                {session && session.id === post.author_id && (
                                    <div className="postActions">
                                    <button onClick={() => deletePost(post.id)} className="deleteButton">
                                        <FaRegTrashAlt /> 
                                    </button>
                                    <button onClick={() => startEditing(post)} className="deleteButton">
                                        <FaRegEdit />
                                    </button>
                                    </div>
                                )}
                            </div>
                            {editingPost && editingPost.id === post.id ? (
                                <div className="textareacontainer">
                                    <textarea value={editingPost.content} onChange={(e) => setEditingPost({...editingPost, content: e.target.value})}/>
                                    <div className="deleteButtons">
                                        <button onClick={cancelEditing}>Annuler</button>
                                        <button onClick={() => saveEdit(post.id, editingPost.content)}>Sauvegarder</button>
                                    </div>
                                </div>
                            ) : (
                                <a href={`/read/${post.id}`}>
                                    <div className={styles.postContent}>
                                        <p>{post.content}</p>
                                        {post.image_id && <img src={post.image} alt="post" className={styles.postContentImage} />}
                                    </div>
                                    <div className={styles.postIcon}>
                                        <span><IoChatbubbleOutline /> {post.comment_count}</span>
                                    </div>
                                </a>
                            )}
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