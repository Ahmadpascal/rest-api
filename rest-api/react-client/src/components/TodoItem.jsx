export default function TodoItem({ todo, onToggle, onDelete }) {
  return (
    <li className="flex items-center gap-3 py-2 border-b">
      <input
        type="checkbox"
        checked={!!todo.completed}
        onChange={(e) => onToggle(todo.id, e.target.checked)}
      />
      <span className="flex-1" style={{
        textDecoration: todo.completed ?
          "line-through" : "none"
      }}>
        {todo.title}
      </span>
      <button className="btn" onClick={() =>
        onDelete(todo.id)}>Hapus</button>
    </li>
  );
}