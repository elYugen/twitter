import axios from "axios";
import React, { useState, useEffect } from 'react';
import styles from './CreatePost.module.css';
import { CiImageOn } from "react-icons/ci";
import { MdOutlineGifBox } from "react-icons/md";

function CreatePost() {
  const [session, setSession] = useState(null);
  const [content, setContent] = useState('');
  const [error, setError] = useState('');
  const [currentTime, setCurrentTime] = useState(new Date());
  const [image, setImage] = useState(null);
  const [previewImage, setPreviewImage] = useState(null);

  useEffect(() => {
    // Récupérer la session
    axios.get("http://localhost/twitter/backend/session.php", { withCredentials: true })
      .then(response => {
        console.log('Données de session:', response.data); 
        setSession(response.data);
      })
      .catch(error => {
        console.error("Erreur lors de la récupération de la session:", error);
      });

    const timer = setInterval(() => {
      setCurrentTime(new Date());
    }, 1000);

    return () => clearInterval(timer);
  }, []);

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setImage(file);
      const reader = new FileReader();
      reader.onloadend = () => {
        setPreviewImage(reader.result);
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!content.trim() && !image) {
      setError('Le contenu ne peut pas être vide et vous devez ajouter du texte ou une image');
      return;
    }
  
    try {
      const formData = new FormData();
      formData.append('content', content);
      formData.append('publishdate', currentTime.toISOString());
      if (image) {
        formData.append('image', image);
      }

      const response = await axios.post('http://localhost/twitter/backend/createPost.php', 
        formData,
        { 
          withCredentials: true,
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }
      );
      
      console.log('Réponse du serveur:', response.data);
  
      if (response.data.success) {
        setContent('');
        setImage(null);
        setPreviewImage(null);
        console.log('Post créé avec succès');
      } else {
        setError(response.data.error || 'Une erreur est survenue');
      }
    } catch (error) {
      console.error('Erreur détaillée:', error.response?.data || error.message);
      setError('Erreur lors de la création du post: ' + (error.response?.data?.error || error.message));
    }
  };

  if (!session) {
    return null; 
  }

  return (
    <div className={styles.createPost}>
      <img src={session.pictures} alt="user" className={styles.profilePicture} />
      <form onSubmit={handleSubmit} className={styles.textareaContainer}>
        <textarea 
          placeholder='Quoi de neuf docteur ?'
          value={content}
          onChange={(e) => setContent(e.target.value)}
        ></textarea>
        {previewImage && (
          <div className={styles.imagePreview}>
            <img src={previewImage} alt="Preview" />
            <button type="button" onClick={() => {setImage(null); setPreviewImage(null);}}>Supprimer</button>
          </div>
        )}
        <hr />
        <div className={styles.createPostOption}>
          <div className={styles.createPostOptionAll}>
            <label htmlFor="fileInput" className={styles.fileInputLabel}>
              <CiImageOn />
            </label>
            <input
              id="fileInput"
              type="file"
              accept="image/*"
              onChange={handleImageChange}
              style={{ display: 'none' }}
            />
            <a href="#"><MdOutlineGifBox /></a>
          </div>
          <button type="submit" style={{fontSize: "20px"}} className="button">
            Poster
          </button>
        </div>
        {error && <p className={styles.errorMessage}>{error}</p>}
      </form>
    </div>
  );
}

export default CreatePost;