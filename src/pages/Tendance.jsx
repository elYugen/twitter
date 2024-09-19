import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';
import Navbar from "../components/Navbar/Navbar";
import Sidebar from "../components/Sidebar/Sidebar";
import styles from '../components/Feed/Feed.module.css';
import { IoChatbubbleOutline } from "react-icons/io5";

const HashtagPosts = () => {
    const { hashtag } = useParams();
    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchPosts = async () => {
            try {
                setLoading(true);
                const response = await axios.get(`http://localhost/twitter/backend/getPostsByHashtag.php?hashtag=${encodeURIComponent(hashtag)}`, {
                    withCredentials: true,
                });

                if (response.data.posts && Array.isArray(response.data.posts)) {
                    setPosts(response.data.posts);
                } else {
                    setPosts([]);
                }
            } catch (err) {
                setError('Erreur lors de la récupération des publications');
            } finally {
                setLoading(false);
            }
        };

        fetchPosts();
    }, [hashtag]);

    if (loading) {
        return <div>Chargement des publications...</div>;
    }

    if (error) {
        return <div>{error}</div>;
    }

    return (
        <div className="layout">
            <Navbar />
            <div className="container">
                <h1>Publications pour #{hashtag}</h1>
                {posts.length === 0 ? (
                    <p>Aucune publication trouvée pour ce hashtag.</p>
                ) : (
                    <ul>
                        {posts.map(post => (
                            <div key={post.id} className="postBox">
                                <div className={styles.postHeader}>
                                    <a href={`/profile/${post.author_id}`}>
                                        <img src={post.pictures || '/default-avatar.png'} alt={post.username} className={styles.profilePicture} />
                                    </a>
                                    <div className={styles.userInfo}>
                                        <a href={`/profile/${post.author_id}`}>
                                            <span className={styles.userInfoUsername}>{post.username}</span>
                                        </a>
                                        <span className={styles.userInfoPublishedAt}>{new Date(post.publishdate).toLocaleString()}</span>
                                    </div>
                                </div>
                                <div className={styles.postContent}>
                                    <p>{post.content}</p>
                                    {post.image_id && <img src={post.image} alt="post" className={styles.postContentImage}/>}
                                </div>
                                <div className={styles.postIcon}>
                                    <span><IoChatbubbleOutline /> {post.comment_count || 0}</span>
                                </div>
                            </div>
                        ))}
                    </ul>
                )}
            </div>
            <Sidebar />
        </div>
    );
};

export default HashtagPosts;