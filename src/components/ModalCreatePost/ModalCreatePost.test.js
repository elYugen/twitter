import React from 'react';
import ReactDOM from 'react-dom';
import ModalCreatePost from './ModalCreatePost';

it('It should mount', () => {
  const div = document.createElement('div');
  ReactDOM.render(<ModalCreatePost />, div);
  ReactDOM.unmountComponentAtNode(div);
});