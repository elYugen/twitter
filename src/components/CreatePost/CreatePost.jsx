import axios from "axios";
import React, { useState, useEffect, useRef } from 'react';
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
  const [uploading, setUploading] = useState(false);
  const fileInputRef = useRef(null);

  useEffect(() => {
    // Récupérer la session
    axios.get("http://localhost/twitter/backend/controller/Session.php", { withCredentials: true })
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

  const handleIconClick = () => {
    fileInputRef.current.click();
  };

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setImage(file);
      const reader = new FileReader();
      reader.onloadend = () => {
        setPreviewImage(reader.result);
        console.log("Image preview générée avec succès : ", reader.result);
      };
      reader.readAsDataURL(file);
      console.log("Image sélectionnée pour l'upload : ", file);
    } else {
      console.error("Aucune image sélectionnée");
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!content.trim() && !image) {
      setError('Le contenu ne peut pas être vide et vous devez ajouter du texte ou une image');
      return;
    }

    setUploading(true); // marque l'upload comme en cours

    try {
      const formData = new FormData();
      formData.append('content', content);
      formData.append('publishdate', currentTime.toISOString());
      if (image) {
        formData.append('image', image);
        console.log("Image ajoutée au FormData : ", image);
      }

      const response = await axios.post('http://localhost/twitter/backend/controller/CreatePost.php', 
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
        setError('');
        console.log('Post créé avec succès');
      } else {
        setError(response.data.error || 'Une erreur est survenue');
        console.error('Erreur serveur: ', response.data.error);
      }

    } catch (error) {
      console.error('Erreur détaillée:', error.response?.data || error.message);
      setError('Erreur lors de la création du post: ' + (error.response?.data?.error || error.message));
    } finally {
      setUploading(false); // Marquer la fin de l'upload
    }
  };

  if (!session) {
    return null; 
  }

  return (
    <div className={styles.createPost}>
      <img src={session.pictures} alt="user" className={styles.profilePicture} />
      <form onSubmit={handleSubmit} className={styles.textareaContainer} encType="multipart/form-data">
        <textarea  placeholder='Quoi de neuf docteur ?' value={content} onChange={(e) => setContent(e.target.value)}></textarea>
        {previewImage && (
          <div className={styles.imagePreview}>
            <img src={previewImage} alt="Preview" />
            <button type="button" onClick={() => {setImage(null); setPreviewImage(null);}}>Supprimer</button>
          </div>
        )}
        <hr />
        <div className={styles.createPostOption}>
          <div className={styles.createPostOptionAll}>
            <CiImageOn onClick={handleIconClick} className={styles.fileInputIcon} />
            <input ref={fileInputRef} type="file" accept="image/*" onChange={handleImageChange} style={{ display: 'none' }}/>
          </div>
          <button type="submit" style={{fontSize: "20px"}} className="button" disabled={uploading}>
            {uploading ? 'Uploading...' : 'Poster'}
          </button>
        </div>
        {error && <p className={styles.errorMessage}>{error}</p>}
      </form>
    </div>
  );
}

export default CreatePost;
