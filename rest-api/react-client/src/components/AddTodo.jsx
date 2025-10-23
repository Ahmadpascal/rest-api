import { useState } from "react";

export default function AddTodo({ onAdd }) {
  const [title, setTitle] = useState("");

  function handleSubmit(e) {
    e.preventDefault();
    if (!title.trim()) return;
    onAdd(title);
    setTitle("");
  }

  return (
    <form
      onSubmit={handleSubmit}
      className="flex items-center gap-2 bg-gray-50 p-3 rounded-xl shadow-sm hover:shadow-md transition"
    >
      <input
        type="text"
        value={title}
        onChange={(e) => setTitle(e.target.value)}
        placeholder="Tulis tugas baru..."
        className="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
      />
      <button
        type="submit"
        className="px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition"
      >
        Tambah
      </button>
    </form>
  );
}
