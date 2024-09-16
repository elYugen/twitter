import Navbar from "../components/Navbar/Navbar"
import Sidebar from "../components/Sidebar/Sidebar"
import ReadPost from "../components/ReadPost/ReadPost"
import CreatePost from "../components/CreatePost/CreatePost"

function Read() {
    return (
        <>
        
        <div className="layout">
            <Navbar /> 
            <div className="container">
            <ReadPost/>
            <CreatePost/>
            </div>
            <Sidebar />
        </div>
        
        </>
    );
}

export default Read