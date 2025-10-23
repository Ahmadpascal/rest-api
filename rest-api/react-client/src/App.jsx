import { useEffect, useState } from "react";
import AddTodo from "./components/AddTodo.jsx";
import TodoList from "./components/TodoList.jsx";
import { getTodos, createTodo, toggleTodo, deleteTodo, updateTodo } from "./lib/api.js";

export default function App() {
  const [todos, setTodos] = useState([]);
  const [loading, setLoading] = useState(false);
  const [err, setErr] = useState("");

  async function load() {
    try {
      setErr("");
      setLoading(true);
      const data = await getTodos();
      setTodos(data);
    } catch (e) {
      setErr(e.message || "Gagal memuat");
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    load();
    document.title = "Todos App";
  }, []);

  async function handleAdd(title) {
    try {
      setErr("");
      await createTodo(title);
      await load();
    } catch (e) {
      setErr(e.message || "Gagal menambah");
    }
  }

  async function handleToggle(id, completed) {
    try {
      setErr("");
      setTodos((prev) =>
        prev.map((t) => (t.id === id ? { ...t, completed } : t))
      );
      await toggleTodo(id, completed);
    } catch (e) {
      setErr(e.message || "Gagal update");
      load();
    }
  }

  async function handleUpdate(id, newTitle) {
    try {
      setErr("");
      await updateTodo(id, newTitle);
      await load();
    } catch (e) {
      setErr(e.message || "Gagal mengubah");
    }
  }

  async function handleDelete(id) {
    try {
      setErr("");
      setTodos((prev) => prev.filter((t) => t.id !== id));
      await deleteTodo(id);
    } catch (e) {
      setErr(e.message || "Gagal hapus");
      load();
    }
  }

  return (
    <main className="min-h-screen flex flex-col items-center bg-gray-100 p-6">
      <div className="w-full max-w-md bg-white shadow-lg rounded-2xl p-6">
        <h1 className="text-3xl font-bold text-gray-800 text-center mb-2">
          📝 Todo List
        </h1>
        <p className="text-sm text-gray-500 text-center mb-6">
          Kelola tugasmu dengan cepat dan mudah!
        </p>

        {/* Form tambah todo */}
        <AddTodo onAdd={handleAdd} />

        {/* Status Loading */}
        {loading && (
          <p className="text-blue-500 text-center font-medium mt-4">
            Memuat…
          </p>
        )}

        {/* Error message */}
        {err && (
          <p className="text-red-500 text-center font-medium mt-4">
            {err}
          </p>
        )}

        {/* Daftar todo */}
        <div className="mt-4">
          <TodoList
            items={todos}
            onToggle={handleToggle}
            onDelete={handleDelete}
            onUpdate={handleUpdate}
          />
        </div>
      </div>

      <footer className="mt-6 text-gray-400 text-sm">
        made with ❤️ by Ahmad Anjari
      </footer>
    </main>
  );
}
