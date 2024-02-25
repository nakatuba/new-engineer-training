# New Engineer Training

## Setup

1. Start the virtual machine

```
vagrant up
```

2. Create a vagrant ssh config file

```
vagrant ssh-config > vagrant_ssh_config
```

3. Run ansible playbook

```
ansible-playbook local.yml
```
