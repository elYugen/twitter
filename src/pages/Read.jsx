import React, { useEffect, useState, useCallback } from 'react';
import { useParams } from 'react-router-dom';
import axios from "axios";
import Navbar from "../components/Navbar/Navbar";
import Sidebar from "../components/Sidebar/Sidebar";
import { FaRegCalendarAlt } from "react-icons/fa";
import { IoChatbubbleOutline } from "react-icons/io5";
import { CiImageOn } from "react-icons/ci";
import { MdOutlineGifBox } from "react-icons/md";
import styles from '../components/Feed/Feed.module.css';

function Read() {
    const [session, setSession] = useState(null);
    const [postData, setPostData] = useState(null);
    const [commentContent, setCommentContent] = useState('');
    const [error, setError] = useState('');
    const { id } = useParams();

    const fetchPostData = useCallback(() => {
        axios.get(`http://localhost/twitter/backend/getPostByIdForReading.php?id=${id}`, { withCredentials: true })
            .then(response => {
                setPostData(response.data);
            })
            .catch(error => {
                console.error("Erreur lors de la récupération de la publication:", error);
                setError("Impossible de charger la publication.");
            });
    }, [id]);

    useEffect(() => {
        // Récupérer la session utilisateur
        axios.get("http://localhost/twitter/backend/session.php", { withCredentials: true })
            .then(response => {
                setSession(response.data);
            })
            .catch(error => {
                console.log("Erreur lors de la récupération de la session:", error);
                setError("Impossible de charger la session utilisateur.");
            });

        // Récupérer les données de la publication
        if (id) {
            fetchPostData();
        }
    }, [id, fetchPostData]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!commentContent.trim()) {
            setError('Le commentaire ne peut pas être vide');
            return;
        }

        try {
            const response = await axios.post('http://localhost/twitter/backend/createCommentOnPostById.php', {
                author_id: session.id,
                publication_id: id,
                content: commentContent
            }, { withCredentials: true });

            if (response.data.success) {
                setCommentContent('');
                setError('');
                fetchPostData(); // Recharger les données du post pour inclure le nouveau commentaire
            } else {
                setError(response.data.error || 'Une erreur est survenue');
            }
        } catch (error) {
            console.error('Erreur lors de l\'ajout du commentaire:', error);
            setError('Erreur lors de l\'ajout du commentaire');
        }
    };

    if (!postData || !session) {
        return <p>Chargement...</p>;
    }

    return (
        <div className="layout">
            <Navbar />
            <div className="container">
                {/* Affichage de la publication à lire */}
                <div className={styles.postBox}>
                    <div className={styles.postHeader}>
                        <a href={`/profile/${postData.author_id}`}>
                            <img src={postData.pictures} alt="user" className={styles.profilePicture} />
                        </a>
                        <div className={styles.userInfo}>
                            <a href={`/profile/${postData.author_id}`}>
                                <span className={styles.userInfoUsername}>{postData.username}</span>
                            </a>
                            <span className={styles.userInfoPublishedAt}>{postData.publishdate}</span>
                        </div>
                    </div>
                    <div className={styles.postContent}>
                        <p>{postData.content}</p>
                        {postData.image && <img src={postData.image} alt="post" className={styles.postContentImage} />}
                    </div>
                    <div className={styles.postIcon}>
                        <span><IoChatbubbleOutline /> {postData.comment_count}</span>
                    </div>
                </div>

                {/* Formulaire pour ajouter un commentaire */}
                <div className="createPost">
                    <img src={session.pictures} alt="user" className={styles.profilePicture} />
                    <form onSubmit={handleSubmit} className="textareaContainer">
                        <textarea
                            placeholder='Ajouter un commentaire...'
                            value={commentContent}
                            onChange={(e) => setCommentContent(e.target.value)}
                        ></textarea>
                        <hr />
                        <div className={styles.createPostOption}>
                            <div className={styles.createPostOptionAll}>
                            <a href="#"><CiImageOn /></a>
                            <a href="#"><MdOutlineGifBox /></a>
                            </div>
                            <button type="submit" style={{ fontSize: "20px" }} className="button">
                                Commenter
                            </button>
                        </div>
                        {error && <p className={styles.errorMessage}>{error}</p>}
                    </form>
                </div>

                {/* Affichage des commentaires */}
                <div className={styles.postBox}>
                    <h3>Commentaires ({postData.comment_count})</h3>
                    <hr /><br />
                    {postData.comments && postData.comments.length > 0 ? (
                        postData.comments.map((comment, index) => (
                            <div key={index} className={styles.commentBox}>
                                <div className={styles.commentHeader}>
                                    <a href={`/profile/${comment.user_id}`}>
                                        <img src={comment.pictures} alt="user" className={styles.profilePicture} />
                                    </a>
                                    <div className={styles.commentUserInfo}>
                                        <a href={`/profile/${comment.user_id}`}>
                                            <span className={styles.userInfoUsername}>{comment.username}</span>
                                        </a>
                                        <span className={styles.commentCreatedAt}>{comment.created_at}</span>
                                    </div>
                                </div>
                                <div className={styles.commentContent}>
                                    <p>{comment.content}</p>
                                </div>
                            </div>
                        ))
                    ) : (
                        <p>Aucun commentaire pour cette publication.</p>
                    )}
                </div>
            </div>
            <Sidebar />
        </div>
    );
}

export default Read;