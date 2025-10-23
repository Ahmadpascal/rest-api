import { Trash2, Edit2, Check, X } from "lucide-react";
import { useState } from "react";

export default function TodoList({ items, onToggle, onDelete, onUpdate }) {
  const [editingId, setEditingId] = useState(null);
  const [newTitle, setNewTitle] = useState("");

  if (!items.length) {
    return (
      <p className="text-center text-gray-400 mt-4">
        Belum ada tugas. Tambahkan di atas 👆
      </p>
    );
  }

  return (
    <ul className="space-y-3 mt-3">
      {items.map((todo) => (
        <li
          key={todo.id}
          className="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl p-3 hover:shadow-md transition"
        >
          <div className="flex items-center gap-3 flex-1">
            <input
              type="checkbox"
              checked={todo.completed}
              onChange={(e) => onToggle(todo.id, e.target.checked)}
              className="h-5 w-5 text-blue-500 focus:ring-blue-400"
            />

            {editingId === todo.id ? (
              <input
                type="text"
                value={newTitle}
                onChange={(e) => setNewTitle(e.target.value)}
                className="flex-1 border border-gray-300 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
              />
            ) : (
              <span
                className={`text-gray-700 ${
                  todo.completed ? "line-through text-gray-400" : ""
                }`}
              >
                {todo.title}
              </span>
            )}
          </div>

          <div className="flex items-center gap-2">
            {editingId === todo.id ? (
              <>
                <button
                  onClick={() => {
                    onUpdate(todo.id, newTitle);
                    setEditingId(null);
                  }}
                  className="p-2 text-green-500 hover:bg-green-500 hover:text-white rounded-lg transition"
                  title="Simpan"
                >
                  <Check size={18} />
                </button>
                <button
                  onClick={() => setEditingId(null)}
                  className="p-2 text-gray-500 hover:bg-gray-300 rounded-lg transition"
                  title="Batal"
                >
                  <X size={18} />
                </button>
              </>
            ) : (
              <>
                <button
                  onClick={() => {
                    setEditingId(todo.id);
                    setNewTitle(todo.title);
                  }}
                  className="p-2 text-blue-500 hover:bg-blue-500 hover:text-white rounded-lg transition"
                  title="Edit"
                >
                  <Edit2 size={18} />
                </button>
                <button
                  onClick={() => onDelete(todo.id)}
                  className="p-2 text-red-500 hover:bg-red-500 hover:text-white rounded-lg transition"
                  title="Hapus"
                >
                  <Trash2 size={18} />
                </button>
              </>
            )}
          </div>
        </li>
      ))}
    </ul>
  );
}
